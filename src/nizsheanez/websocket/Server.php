<?php
namespace nizsheanez\websocket;

class Server extends \nizsheanez\daemon\Server
{
    public $routeClass = '\nizsheanez\websocket\Application';
    public $yiiApplicationClass = '\nizsheanez\websocket\Application';

}
