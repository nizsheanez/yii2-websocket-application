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
        $this->setComponents([
            'request' => [
                'class' => 'nizsheanez\websocket\Request',
            ],
            'response' => [
                'class' => 'nizsheanez\websocket\Response',
            ],
        ]);
    }

}
