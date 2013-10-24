<?php
namespace nizsheanez\websocket;

class AssetBundle extends yii\web\AssetBundle
{
    public $sourcePath = '@vendor/nizsheanez/yii2-websocket-application/src/nizsheanez';
    public $baseUrl = '@web';
    public $js = array(
        'js/WebSocketConnection.js',
    );
}
