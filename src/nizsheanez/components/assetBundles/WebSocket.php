<?php
namespace nizsheanez\components\assetBundles;

class WebSocket extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/nizsheanez/yii2-websocket-application/src/nizsheanez';
    public $baseUrl = '@web';
    public $js = array(
        'js/WebSocketConnection.js',
    );

    public $depends = array(
        'nizsheanez\components\assetBundles\PhpDaemon',
    );
}
