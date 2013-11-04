<?php
namespace nizsheanez\websocket;

class Server extends \nizsheanez\daemon\Server
{
    public $routeClass = '\nizsheanez\websocket\Route';
    public $yiiApplicationClass = '\nizsheanez\websocket\Application';

}
