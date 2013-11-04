<?php
namespace nizsheanez\daemon;

class Server extends \PHPDaemon\Core\AppInstance
{
    public $enableRPC = true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions = []; // Здесь будем хранить указатели на сессии подключившихся клиентов

    public $yiiDebug = false;

    public $routeClass = '\nizsheanez\daemon\Route';
    public $yiiApplicationClass = '\nizsheanez\daemon\Application';

    public function onReady()
    {
        $appInstance = $this;
        \PHPDaemon\Servers\WebSocket\Pool::getInstance()->addRoute('', function ($client) use ($appInstance) {
            $route = new $this->routeClass($client, $appInstance); // Создаем сессию
            $route->id = uniqid(); // Назначаем ей уникальный ID
            $appInstance->sessions[$route->id] = $route; //Сохраняем в массив
            return $route;
        });

        $this->initYiiApplication();
    }

    public function getYiiConfig()
    {

    }

    public function initYiiApplication()
    {
        if ($this->yiiDebug) {
            // comment out the following line to disable debug mode
            defined('YII_DEBUG') or define('YII_DEBUG', true);
        }

        require(__DIR__ . '/../../../../../../vendor/yiisoft/yii2/yii/Yii.php');

        return new $this->yiiApplicationClass($this->getYiiConfig());
    }

}
