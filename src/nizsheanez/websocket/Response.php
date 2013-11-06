<?php

namespace nizsheanez\daemon\websocket;

class Response extends \nizsheanez\daemon\base\Response
{
    /**
     * @var \PHPDaemon\WebSocket\Route
     */
    protected $daemonRoute;

    public function setDaemonRoute($route)
    {
        $this->daemonRoute = $route;
    }

    public function send()
    {
        $this->daemonRoute->client->sendFrame($this, 'STRING');
    }
}
