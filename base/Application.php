<?php
namespace nizsheanez\daemon\base;

use Exception;

class Application extends \yii\base\Application
{
    /**
     * Handles the specified request.
     * @param \nizsheanez\daemon\base\Request $request the request to be handled
     * @return \nizsheanez\daemon\base\Response the resulting response
     */
    public function handleRequest($request)
    {
        list ($route, $params) = $request->resolve();
        $this->requestedRoute = $route;
        try {
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
                'class' => 'nizsheanez\daemon\base\Request',
            ),
            'response' => array(
                'class' => 'nizsheanez\daemon\base\Response',
            ),
        ));
    }

}
