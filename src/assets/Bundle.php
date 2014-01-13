<?php
namespace nizsheanez\wamp\assets;

class Bundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/nizsheanez/yii2-websocket-application/src/nizsheanez/ws/assets';
    public $baseUrl = '@web';
    public $js = array(
        'js/socketResource.js',
        'js/autobahn.js',
    );
}
