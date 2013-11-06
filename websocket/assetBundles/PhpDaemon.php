<?php
namespace nizsheanez\websocket\assetBundles;

use yii\web\AssetBundle;

class PhpDaemon extends AssetBundle
{
    public $sourcePath = '@vendor/kakserpom/phpdaemon/clientside-connectors/websocket';
    public $baseUrl = '@web';

    public $js = [
        'js/websocket.js',
    ];
}
