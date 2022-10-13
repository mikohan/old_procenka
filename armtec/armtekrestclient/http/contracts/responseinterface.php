<?php

namespace armtekrestclient\http\contracts;

/**
 * Http Response returned from {@see HttpClientInterface::request}.
 *
 * @since 1.0.0
 */
interface responseinterface
{
    /**
     * @return int
     */
    public function statusCode();

    /**
     * @return string
     */
    public function contentType();

    /**
     * @return string
     */
    public function content();

    /**
     * @return array
     */
    public function headers();

    /**
     * @param $name
     *
     * @return mixed
     */
    public function header($name);
}
