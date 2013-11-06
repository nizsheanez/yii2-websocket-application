<?php
namespace nizsheanez\daemon\base;

class Server extends \PHPDaemon\Core\AppInstance
{
    public $enableRPC = true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions = []; // Здесь будем хранить указатели на сессии подключившихся клиентов

    public $yiiDebug = false;

    public $routeClass = '\nizsheanez\daemon\base\Route';

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
        switch ($path)
        {
            case '':
                $route = new $this->routeClass($client, $this); // Создаем сессию
                $route->id = uniqid(); // Назначаем ей уникальный ID
                $this->sessions[$route->id] = $route; //Сохраняем в массив
                return $route;
        }

    }
}
