<?php

namespace nizsheanez\ws;

use Yii;

class Response extends \yii\web\Response
{
    public $isSuccess;

    public function send() {

    }

    public function success() {
        $this->isSuccess = true;
    }
    public function fail(\Exception $e) {
        $this->data = $e->getTraceAsString();
        $this->isSuccess = false;
    }
}
