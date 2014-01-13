<?php
$main = require 'main.php';


$websocket = array(
    'id'                  => 'app-frontend-websocket',
    'components'          => [
        'request' => array(
            'class' => 'nizsheanez\wamp\Request',
        ),
        'response' => array(
            'class' => 'nizsheanez\wamp\Response',
        ),
    ],
);

return \yii\helpers\ArrayHelper::merge($main, $websocket);
