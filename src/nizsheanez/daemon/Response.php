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

    public function getMessage()
    {
        if ($this->errorMessage) {
            $data = $this->protocol->getResponse($this->result, $this->errorMessage);
        } else {
            $data = $this->protocol->getResponse($this->result);
        }

        return json_encode($data);
    }

    public function send()
    {
        if ($this->data['result']) {
            file_put_contents('php://stdout', $this->getMessage());
        } else {
            file_put_contents('php://stderr', $this->getMessage());
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
