<?php
namespace nizsheanez\daemon;

use Exception;

class Server extends \PHPDaemon\Core\AppInstance
{
    public $enableRPC = true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions = []; // Здесь будем хранить указатели на сессии подключившихся клиентов

    public $yiiDebug = false;

    public $routeClass = '\nizsheanez\daemon\Application';
    public $yiiApplicationClass = '\nizsheanez\daemon\Application';
    public $yiiConfigFile;

    public function onReady()
    {
        $appInstance = $this;
        \PHPDaemon\Servers\WebSocket\Pool::getInstance()->addRoute('', function ($client) use ($appInstance) {
            $class = $this->routeClass;
            $route = new $class($client, $appInstance); // Создаем сессию
            $route->id = uniqid(); // Назначаем ей уникальный ID
            $appInstance->sessions[$route->id] = $route; //Сохраняем в массив
            return $route;
        });

        if (!$this->yiiConfigFile) {
            throw new Exception('Need to define yiiConfigFile');
        }
        $this->initYiiApplication($this->yiiConfigFile, $this->yiiApplicationClass);
    }

    public function initYiiApplication($config, $applicationClass)
    {
        if ($this->yiiDebug) {
            // comment out the following line to disable debug mode
            defined('YII_DEBUG') or define('YII_DEBUG', true);
        }

        require(__DIR__ . '/../../../vendor/autoload.php');
        require(__DIR__ . '/../../../vendor/yiisoft/yii2/yii/Yii.php');

        $config = require(__DIR__ . $config);
        return new $applicationClass($config);
    }

}
