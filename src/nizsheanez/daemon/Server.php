<?php
namespace nizsheanez\daemon;

class Server extends \PHPDaemon\Core\AppInstance
{
    public $enableRPC = true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions = []; // Здесь будем хранить указатели на сессии подключившихся клиентов

    public $yiiDebug = false;

    public $routeClass = '\nizsheanez\daemon\Route';

    public function onReady()
    {
        $this->initRoutes();
    }

    public function initRoutes()
    {
        $appInstance = $this;
        $path = '';
        \PHPDaemon\Servers\WebSocket\Pool::getInstance()->addRoute($path, function ($client) use ($path, $appInstance) {
            return $appInstance->getRoute($path, $client);
        });
    }

    public function getRoute($path, $client)
    {
        $route = new $this->routeClass($client, $this); // Создаем сессию
        $route->id = uniqid(); // Назначаем ей уникальный ID
        $appInstance->sessions[$route->id] = $route; //Сохраняем в массив
        return $route;
    }
}
