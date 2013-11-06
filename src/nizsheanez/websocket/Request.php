<?php

namespace nizsheanez\daemon\websocket;

class Request extends \nizsheanez\daemon\base\Request
{
    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     */
    public function resolve()
    {
        return [$this->getRoute(), []];
    }
}
