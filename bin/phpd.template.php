#!/usr/bin/env php
<?php
// comment out the following line to disable debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

require __DIR__ . '../vendor/autoload.php';
require(__DIR__ . '../vendor/yiisoft/yii2/yii/Yii.php');

$config = require __DIR__ . '../frontend/config/frontend_configs.php';
new \nizsheanez\websocket\Application($config);

require __DIR__ . '../vendor/kakserpom/phpdaemon/bin/phpd';
