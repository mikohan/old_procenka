<?php

namespace armtekrestclient\http\contracts;

/**
 * The request interface
 * @since 1.0.0
 */
interface requestinterface
{
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_GET = 'GET';
    const METHOD_HEAD = 'HEAD';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
}
