<?php
namespace nizsheanez\websocket;

class Request extends \yii\web\Request
{
    public function getScriptUrl()
    {
        return '/index.php';
    }

}
