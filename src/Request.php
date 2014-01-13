<?php
namespace nizsheanez\wamp;

class Request extends \yii\web\Request
{
    public $uri;

    public function getScriptUrl()
    {
        return '/index.php';
    }

    protected function resolveRequestUri()
    {
        return $this->uri;
    }

    /**
     * Unify interface for cli and websocket app,
     * this method return query parameters
     *
     * @return mixed
     */
    public function getParams()
    {
        return $_GET;
    }

}
