<?php

namespace nizsheanez\websocket;

class Application extends \nizsheanez\daemon\Application
{
    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        $this->setComponents(array(
            'request' => array(
                'class' => 'nizsheanez\websocket\Request',
            ),
            'response' => array(
                'class' => 'nizsheanez\websocket\Response',
            ),
        ));
    }

}
