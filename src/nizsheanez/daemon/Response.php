<?php

namespace nizsheanez\daemon;

use nizsheanez\jsonRpc\Protocol;
use Yii;

class Response extends \yii\base\Response
{
    protected $result;
    protected $errorMessage;
    protected $protocol;

    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    public function send()
    {
        if ($this->result) {
            file_put_contents('php://stdout', $this->protocol->getMessage($this->result));
        } else {
            file_put_contents('php://stderr', $this->protocol->getMessage($this->result, $this->errorMessage));
        }
    }

    public function success($data)
    {
        $this->result = $data;
    }

    public function fail($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
