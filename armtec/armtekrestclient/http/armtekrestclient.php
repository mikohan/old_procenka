<?php

namespace armtekrestclient\http;

use \armtekrestclient\http\config;
use \armtekrestclient\http\exception;


/**
* ArmtekRestClient.
*
* @since 1.0.0
*/
class armtekrestclient
{
    /**
    * ArmtekRestClient configuration
    *
    * @var null|object
    */
    private $_config = null;

    public function __construct(\armtekrestclient\http\config\config $config)
    {
        $this->_config = $config;
    }


    /**
    * @param string|array $request
    *
    * @return bool
    */
    protected function valid($request_params)
    {
        return (boolean) array_key_exists('url', $request_params);
    }

    private function requestInstance($request)
    {
        return new request($request, $this->_config);
    }

    public function __call($method, $arguments)
    {
        $request_params = array_pop($arguments);

        if (!is_array($request_params)) {
            $request_params = ['url' => $request_params];
        }

        if ($this->valid($request_params)) {

            $request_params['method'] = request::method($method);
            $this->request = $this->requestInstance($request_params);

            return $this->request->send();
        }

        throw new \armtekrestclient\http\exception\armtekexception('Ошибка! Запрос не может быть создан');
    }
}
