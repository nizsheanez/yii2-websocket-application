<?php

namespace nizsheanez\daemon;

use nizsheanez\jsonRpc\Protocol;
use Yii;

class Request extends \yii\base\Request
{
    /**
     * @var Protocol
     */
    protected $protocol;
    protected $message;

    public function setMessage($message)
    {
        $this->message = $message;
        $this->protocol = Protocol::server($this->message);
        Yii::$app->response->setProtocol($this->protocol);
    }


    public function getRequestId()
    {
        return $this->protocol->getRequestId();
    }

    public function getRoute()
    {
        return $this->protocol->getMethod();
    }

    public function getParams()
    {
        return $this->protocol->getParams();
    }

    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     */
    public function resolve()
    {
        return array($this->getRoute(), []);
    }

}
