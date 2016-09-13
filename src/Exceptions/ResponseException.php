<?php

namespace ChartMogul\Exceptions;

/**
* @codeCoverageIgnore
*/
interface ResponseException
{
    public function getStatusCode();
    public function getResponse();
}
