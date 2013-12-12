<?php

namespace nizsheanez\daemon;

use nizsheanez\jsonRpc\Protocol;
use Yii;

class Request extends \yii\web\Request
{
    use \nizsheanez\jsonRpc\traits\Request;

    public function getRoute()
    {
        return $this->getMethod();
    }

    protected function resolveRequestUri()
    {
        return $this->getRoute();
    }

    protected function resolvePathInfo()
    {
        return $this->getRoute();
    }
}
