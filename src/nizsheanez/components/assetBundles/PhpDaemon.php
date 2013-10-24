<?php
namespace nizsheanez\components\assetBundles;

use yii\web\AssetBundle;

class PhpDaemon extends AssetBundle
{
    public $sourcePath = '@vendor/kakserpom/phpdaemon/clientside-connectors/websocket';
    public $baseUrl = '@web';

    public $js = array(
        'js/websocket.js',
    );
}
