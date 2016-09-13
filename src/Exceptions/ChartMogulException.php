<?php

namespace ChartMogul\Exceptions;

use Psr\Http\Message\ResponseInterface;

class ChartMogulException extends \RuntimeException implements ResponseException
{

    private $statusCode = 0;

    private $response = '';


    public function __construct($message, ResponseInterface $response = null, \Exception $previous = null)
    {

        if ($response) {
            $response->getBody()->rewind();
            $this->statusCode = $response->getStatusCode();

            $body = $response->getBody()->getContents();
            $json = json_decode($body, true);

            $this->response = json_last_error() === JSON_ERROR_NONE? $json : $body;
        }

        parent::__construct($message, $this->statusCode, $previous);
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
