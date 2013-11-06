<?php

namespace nizsheanez\daemon\websocket;

class Application extends \nizsheanez\daemon\base\Application
{
    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        $this->setComponents([
            'request' => [
                'class' => 'nizsheanez\daemon\websocket\Request',
            ],
            'response' => [
                'class' => 'nizsheanez\daemon\websocket\Response',
            ],
        ]);
    }

}
