<?php
namespace nizsheanez\daemon;

use Yii;
use Exception;

class Application extends \yii\web\Application
{
    /**
     * Handles the specified request.
     * @param Request $request the request to be handled
     * @return Response the resulting response
     */
    public function handleRequest($request)
    {
        Yii::setAlias('@webroot', dirname($request->getScriptFile()));
        Yii::setAlias('@web', $request->getBaseUrl());
        list ($route, $params) = $request->resolve();
        try {
            Yii::trace("Route requested: '$route'", __METHOD__);
            $this->requestedRoute = $route;
            $result = $this->runAction($route, $params);
            if ($result instanceof \yii\base\Response) {
                return $result;
            } else {
                $this->response->success($result);
            }
        } catch (Exception $e) {
            $this->response->fail($e);
        }
        return $this->response;
    }

    /**
     * Returns the response component.
     * @return Response the response component
     */
    public function getResponse()
    {
        return $this->getComponent('response');
    }


    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        $this->setComponents(array(
            'request' => array(
                'class' => 'nizsheanez\daemon\Request',
            ),
            'response' => array(
                'class' => 'nizsheanez\daemon\Response',
            ),
        ));
    }

}
