<?php
namespace nizsheanez\ws;

use common\components\Wamp;
use PHPDaemon\Core\Daemon;
use Yii;

class Route extends \PHPDaemon\WebSocket\Route
{
    public $id; // id of connection session

    public $prefixes = array();

    public $yiiAppConfig;
    public $yiiAppClass;

    /**
     * @var \nizsheanez\ws\Server
     */
    public $appInstance;

    /**
     * @var Wamp
     */
    public $wamp;

    public function onHandshake()
    {
        $wamp = $this->wamp = new Wamp($this);
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
        $class = $this->yiiAppClass;
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

    /**
     * {@inheritdoc}
     */
    public function onCall($id, $topic, array $params)
    {
        Yii::$app->request->setUrl(str_replace($this->client->server['HTTP_ORIGIN'], '', $topic));
        Daemon::log(Yii::$app->request->getUrl());

        $_GET = $params;
        try {
            Yii::$app->run();
        } catch (\Exception $e) {
            $this->wamp->error($id, Yii::$app->response->data);
            Daemon::log($e);
            return true;
        }
        $this->wamp->result($id, Yii::$app->response->data);
    }


    public function getUri($uri)
    {
        return (array_key_exists($uri, $this->prefixes) ? $this->prefixes[$uri] : $uri);
    }

    public function onSubscribe($id)
    {
        $this->appInstance->pubsub->sub($id, $this->client, function () {
        });
    }

    public function onUnsubscribe($id)
    {
        $this->appInstance->pubsub->unsub($id, $this);
    }

    public function onPublish($id, $event, array $exclude, array $eligible)
    {
        $this->appInstance->pubsub->pub($id, $event);
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

    public function onFinish()
    {
        $this->appInstance->pubsub->unsubFromAll($this);

        return $this;
    }
}
