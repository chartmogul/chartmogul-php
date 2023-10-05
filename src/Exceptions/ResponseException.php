<?php

namespace ChartMogul\Exceptions;

/**
 * ResponseException Interface
 *
 * @codeCoverageIgnore
 */
interface ResponseException
{
    /**
     * GET HTTP Status Code
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Get HTTP response
     *
     * @return string|array
     */
    public function getResponse();
}
