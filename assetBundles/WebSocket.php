<?php
namespace nizsheanez\daemon\assetBundles;

class WebSocket extends \yii\web\AssetBundle
{
    public $sourcePath = '@nizsheanez/daemon/web';
    public $baseUrl = '@web';
    public $js = [
        'js/WebSocketConnection.js',
    ];

    public $depends = [
        'nizsheanez\daemon\assetBundles\PhpDaemon',
    ];
}
