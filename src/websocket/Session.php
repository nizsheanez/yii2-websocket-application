<?php
namespace nizsheanez\websocket;

class Session extends \yii\web\Session
{
    /**
     * $_SESSION - create in connection, and you should not to start it again
     * @see \PHPDaemon\Servers\WebSocket\Connection::onWakeup
     *
     * If you wan't to use UserCustomStorage, @see \PHPDaemon\Traits\Session::sessionDecode
     */
    public function open()
    {
        $this->updateFlashCounters();
    }

}