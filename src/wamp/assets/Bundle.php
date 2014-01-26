<?php
namespace nizsheanez\wamp\assets;

class Bundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@nizsheanez/wamp/assets/js';
    public $baseUrl = '@web';
    public $js = array(
        'socketResource.js',
        'autobahn.js',
    );
}
