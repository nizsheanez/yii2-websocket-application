<?php
namespace nizsheanez\wamp;

interface ContextInterface {

    /**
     * @param string $message
     */
    public function send($message);

    /**
     * @param string $prefix
     * @param string $value
     */
    public function setPrefix($prefix, $value);

    /**
     * @param string $prefix
     *
     * @return string|false
     */
    public function getPrefix($prefix);

    /**
     * Get connection id
     *
     * @return string
     */
    public function getID();

    public function call($id, $topic, array $params);
    public function subscribe($uri);
    public function unsubscribe($uri);
    public function publish($uri, $data, $exclude, $eligible);

}
