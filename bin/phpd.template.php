#!/usr/bin/env php
<?php
// comment out the following line to disable debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('DAEMON') or define('DAEMON', true);

require __DIR__ . '/../vendor/autoload.php';
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

//set aliaces, app will create when it will need
require realpath(__DIR__ . '/../frontend/config/main.php');

$configFile = __DIR__ . '/../common/config/phpd.conf';
require __DIR__ . '/../vendor/kakserpom/phpdaemon/bin/phpd';
