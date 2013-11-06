<?php

namespace nizsheanez\daemon\base;

use nizsheanez\jsonRpc\Protocol;
use Yii;

class Request extends \yii\base\Request
{
    use \nizsheanez\jsonRpc\traits\Request;

    public function getRoute()
    {
        return $this->getMethod();
    }

    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     */
    public function resolve()
    {
        return [$this->getRoute(), []];
    }

}
