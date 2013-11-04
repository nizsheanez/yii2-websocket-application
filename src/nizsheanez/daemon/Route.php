<?php
namespace nizsheanez\daemon;

class Route extends \PHPDaemon\WebSocket\Route
{
    public $id; // Здесь храним ID сессии

    // Этот метод срабатывает сообщении от клиента
    public function onFrame($message, $type)
    {
        Yii::$app->request->setRequestMessage($message);
        Yii::$app->response->setRequestMessage($message);
        Yii::$app->response->setDaemonRoute($this);
        Yii::$app->run();
    }

    // Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish()
    {
        // Удаляем сессию из массива
        unset($this->appInstance->sessions[$this->id]);
    }

}