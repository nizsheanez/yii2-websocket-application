<?php

namespace nizsheanez\daemon;

use nizsheanez\jsonRpc\Protocol;
use Yii;

class Response extends \yii\base\Response
{
    use \nizsheanez\jsonRpc\traits\Serializable;

    public function send()
    {
        file_put_contents($this->isSuccessResponse() ? 'php://stdout' : 'php://stderr', $this);
    }

    public function success($data)
    {
        $this->result = $data;
    }

    public function fail($exception)
    {
        $this->exception = $exception;
    }
}
