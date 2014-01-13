<?php
namespace nizsheanez\wamp;

use Exception;

class Protocol
{
    const MSG_WELCOME = 0;
    const MSG_PREFIX = 1;
    const MSG_CALL = 2;
    const MSG_CALL_RESULT = 3;
    const MSG_CALL_ERROR = 4;
    const MSG_SUBSCRIBE = 5;
    const MSG_UNSUBSCRIBE = 6;
    const MSG_PUBLISH = 7;
    const MSG_EVENT = 8;

    protected $route;

    /**
     * Constructor
     *
     * @param \nizsheanez\wamp\Route $route
     *
     * @return void
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    public function onOpen()
    {
        $this->route->send(json_encode(array(
            static::MSG_WELCOME,
            $this->route->id,
            1,
            'yii2-websocket-application'
        )));
    }

    /**
     * Respond with an error to a client call
     *
     * @param string $id The   unique ID given by the client to respond to
     * @param string $errorUri The URI given to identify the specific error
     * @param string $desc A developer-oriented description of the error
     * @param string $details An optional human readable detail message to send back
     */
    public function callError($id, $errorUri, $desc = '', $details = null)
    {
        $data = array(
            static::MSG_CALL_ERROR,
            $id,
            $errorUri,
            $desc
        );

        if (null !== $details) {
            $data[] = $details;
        }

        return $this->route->send(json_encode($data));
    }

    /**
     * @param string $topic The topic to broadcast to
     * @param mixed $msg Data to send with the event.  Anything that is json'able
     */
    public function event($topic, $msg)
    {
        return $this->route->send(json_encode(array(
            static::MSG_EVENT,
            (string)$topic,
            $msg
        )));
    }

    /**
     * @param string $curie
     * @param string $uri
     */
    public function prefix($curie, $uri)
    {
        $this->route->prefixes[$curie] = (string)$uri;

        return $this->route->send(json_encode(array(
            static::MSG_PREFIX,
            $curie,
            (string)$uri
        )));
    }

    /**
     * Get the full request URI from the connection object if a prefix has been established for it
     *
     * @param string $uri
     *
     * @return string
     */
    public function getUri($uri)
    {
        return (array_key_exists($uri, $this->route->prefixes) ? $this->route->prefixes[$uri] : $uri);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     * @throws JsonException
     */
    public function onMessage($msg)
    {
        if (null === ($json = @json_decode($msg, true))) {
            throw new \Exception("Can't to json_decode message: " . $msg);
        }

        if (!is_array($json) || $json !== array_values($json)) {
            throw new \UnexpectedValueException("Invalid WAMP message format");
        }

        switch ($json[0]) {
            case static::MSG_PREFIX:
                $this->route->prefixes[$json[1]] = $json[2];
                break;

            case static::MSG_CALL:
                array_shift($json);
                $callID = array_shift($json);
                $procURI = array_shift($json);

                if (count($json) == 1 && is_array($json[0])) {
                    $json = $json[0];
                }

                $this->route->call($callID, $procURI, $json);
                break;

            case static::MSG_SUBSCRIBE:
                $this->route->subscribe($this->route->getUri($json[1]));
                break;

            case static::MSG_UNSUBSCRIBE:
                $this->route->subscribe($this->route->getUri($json[1]));
                break;

            case static::MSG_PUBLISH:
                $exclude = (array_key_exists(3, $json) ? $json[3] : null);
                if (!is_array($exclude)) {
                    if (true === (boolean)$exclude) {
                        $exclude = array($this->route->id);
                    } else {
                        $exclude = array();
                    }
                }

                $eligible = (array_key_exists(4, $json) ? $json[4] : array());

                $this->route->publish($this->route->getUri($json[1]), $json[2], $exclude, $eligible);
                break;

            default:
                throw new Exception('Invalid message type');
        }
    }

    public function result($callId, $data)
    {
        return $this->route->send(json_encode([static::MSG_CALL_RESULT, $callId, $data]));
    }

    public function error($callId, $data)
    {
        return $this->route->send(json_encode([static::MSG_CALL_ERROR, $callId, $data]));
    }

}