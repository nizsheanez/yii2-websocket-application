<?php
namespace nizsheanez\wamp\assets;

class Bundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@nizsheanez/wamp';
    public $baseUrl = '@web';
    public $js = array(
        'assets/js/socketResource.js',
        'assets/js/autobahn.js',
    );
}
