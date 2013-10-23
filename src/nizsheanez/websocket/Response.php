<?php

namespace nizsheanez\websocket;

class Response extends \nizsheanez\daemon\Response
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
        $this->daemonRoute->client->sendFrame($this->protocol->getMessage($this->result), 'STRING');
    }
}
