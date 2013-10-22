<?php

namespace nizsheanez\daemon;

class Application extends \yii\base\Application
{
    /**
     * Handles the specified request.
     * @param \nizsheanez\daemon\Request $request the request to be handled
     * @return \nizsheanez\daemon\Response the resulting response
     */
    public function handleRequest($request)
    {
        list ($route, $params) = $request->resolve();
        $this->requestedRoute = $route;
        $result = $this->runAction($route, $params);
        if ($result instanceof \yii\base\Response) {
            return $result;
        } else {
            $response = $this->response;
            $response->success($result);
            return $response;
        }
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
