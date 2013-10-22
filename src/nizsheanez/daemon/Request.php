<?php

namespace nizsheanez\daemon;

class Request extends \yii\base\Request
{
    protected $_route;
    protected $_callback_id;
    protected $_params;

    public function setMessage($message)
    {
        \Yii::configure($this, json_decode($message, true));
    }

    public function setCallbackId($val)
    {
        $this->_callback_id = $val;
    }

    public function getCallbackId()
    {
        return $this->_callback_id;
    }

    public function setRoute($val)
    {
        $this->_route = $val;
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function setParams($val)
    {
        $this->_params = $val;
    }

    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     */
    public function resolve()
    {
        return array($this->_route, array());
    }

}
