<?php
namespace nizsheanez\wamp\assets;

class Bundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@nizsheanez/wamp/assets';
    public $baseUrl = '@web';
    public $js = array(
        'js/socketResource.js',
        'js/autobahn.js',
    );
}
