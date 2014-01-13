<?php
namespace nizsheanez\ws;

use PHPDaemon\Core\Daemon;

class Server extends \PHPDaemon\Core\AppInstance
{
    public $enableRPC = true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions = []; // Здесь будем хранить указатели на сессии подключившихся клиентов

    public $yiiDebug = false;

    public $routeClass = '\nizsheanez\ws\Route';
    public $pubsub;

    public function __construct($name = '')
    {
        $this->pubsub = new \PHPDaemon\PubSub\PubSub();
        parent::__construct($name);
    }

    public function onReady()
    {
        $this->initRoutes();
    }

    public function initRoutes()
    {
        $appInstance = $this;
        $path        = '';
        \PHPDaemon\Servers\WebSocket\Pool::getInstance()->addRoute($path, function ($client) use ($path, $appInstance) {
            return $appInstance->getRoute($path, $client);
        });
    }

    public function getRoute($path, $client)
    {
        switch ($path) {
            case '':
                $route                      = new $this->routeClass($client, $this);
                $route->id                  = uniqid();
                $route->yiiAppClass         = $this->config->yiiappclass->value;
                $route->yiiAppConfig        = $this->config->yiiappconfig->value;
                $this->sessions[$route->id] = $route;
                return $route;
        }

    }

}

