<?php
namespace nizsheanez\wamp;

use PHPDaemon\Core\Daemon;
use Yii;

class Route extends \PHPDaemon\WebSocket\Route implements ContextInterface
{
    public $id; // id of connection session

    protected $prefixes = array();

    public $yiiAppConfig;
    public $yiiAppClass;

    /**
     * @var \nizsheanez\wamp\Server
     */
    public $appInstance;

    /**
     * @var \nizsheanez\wamp\Protocol
     */
    public $wamp;

    public function onHandshake()
    {
        $this->client->onSessionStart(function ($event) {}); //temporary fix of non started sessions
        $wamp = $this->wamp = new Protocol($this);
        $wamp->onOpen();
    }


    public function onFrame($message, $type)
    {
        $this->beforeOnMessage();
        $message = substr($message, 0, strrpos($message, ']') + 1);

        $this->wamp->onMessage($message);
        $this->afterOnMessage();
    }

    /**
     * Create Yii application
     */
    public function beforeOnMessage()
    {
        $config = require Yii::getAlias($this->yiiAppConfig);
        $class  = $this->yiiAppClass;
        new $class($config);
    }

    /**
     * Garbage Collection
     *
     * close sessions, destroy application, etc.
     */
    public function afterOnMessage()
    {
        $this->client->sessionCommit();
        Yii::$app->session->close();
        foreach (Yii::$app->getComponents() as $component) {
            unset($component);
        }
        Yii::$app = null;
    }


    public function onFinish()
    {
        $this->appInstance->pubsub->unsubFromAll($this);

        return $this;
    }


    public function setPrefix($prefix, $value)
    {
        $this->prefixes[$prefix] = $value;
    }

    public function getPrefix($prefix)
    {
        if (array_key_exists($prefix, $this->prefixes)) {
            return $this->prefixes[$prefix];
        } else {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }


    public function call($id, $topic, array $params)
    {
        Yii::$app->request->setUrl(str_replace($this->client->server['HTTP_ORIGIN'], '', $topic));
        Daemon::log(Yii::$app->request->getUrl());

        Yii::$app->request->setQueryParams($params);
        try {
            Yii::$app->run();
        } catch (\Exception $e) {
            $this->wamp->error($id, Yii::$app->response->data);
            Daemon::log($e);
            return true;
        }
        $this->wamp->result($id, Yii::$app->response->data);
    }

    public function subscribe($id)
    {
        $this->appInstance->pubsub->sub($id, $this->client, function () {
        });
    }

    public function unsubscribe($id)
    {
        $this->appInstance->pubsub->unsub($id, $this);
    }

    public function publish($uri, $event, $exclude, $eligible)
    {
        $this->appInstance->pubsub->pub($uri, $event);
    }

    public function onClose()
    {
        $this->appInstance->pubsub->unsubFromAll($this);
    }

    public function onError(\Exception $e)
    {
        return $this->_decorating->onError($this->client, $e);
    }

    public function send($message)
    {
        $this->client->sendFrame($message, 'STRING');

        return $this;
    }
}
