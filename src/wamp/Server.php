<?php
namespace nizsheanez\wamp;

class Server extends \nizsheanez\websocket\Server
{
    public $routeClass = '\nizsheanez\wamp\Route';
    public $pubsub;

    public function __construct($name = '')
    {
        $this->pubsub = new \PHPDaemon\PubSub\PubSub();
        parent::__construct($name);
    }

}

