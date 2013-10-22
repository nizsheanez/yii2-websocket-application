<?php

namespace nizsheanez\daemon;

use Yii;

class Response extends \yii\base\Response
{
    protected $data;

    public function getMessage()
    {
        if (Yii::$app->request->getRequestId()) {
            $this->data['id'] = Yii::$app->request->getRequestId();
        }
        $this->data['method'] = Yii::$app->request->getRoute();

        return json_encode($this->data);
    }

    public function send()
    {
        if ($this->data['status'] == 'success') {
            file_put_contents('php://stdout', $this->getMessage());
        } else {
            file_put_contents('php://stderr', $this->getMessage());
        }
    }

    public function success($data)
    {
        $this->data = [
//            'status' => 'success',
            'params' => $data
        ];
    }

    public function fail($data)
    {
        $this->data = [
//            'status' => 'error',
            'error' => $data
        ];
    }
}
