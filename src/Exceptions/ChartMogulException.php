<?php

namespace ChartMogul\Exceptions;

use Psr\Http\Message\ResponseInterface;

/**
 * ChartMogulException
 */
class ChartMogulException extends \RuntimeException implements ResponseException
{
    /**
     * @var int
     */
    private $statusCode = 0;

    /**
     * @var string|array
     */
    private $response = '';


    /**
     * ChartMogulException
     * You can match against subclasses or parsed response.
     *
     * @param string                 $message  Exception message
     * @param ResponseInterface|null $response ResponseInterface object
     * @param \Exception|null        $previous
     */
    public function __construct($message, ?ResponseInterface $response = null, ?\Exception $previous = null)
    {
        if ($response) {
            $response->getBody()->rewind();
            $this->statusCode = $response->getStatusCode();

            $body = $response->getBody()->getContents();
            $json = json_decode($body, true);

            $this->response = json_last_error() === JSON_ERROR_NONE ? $json : $body;
            // Adding body to message to get the whole error message to API user.
            $message = $message . " Response:\n " . $body;
        }

        parent::__construct($message, $this->statusCode, $previous);
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function getResponse()
    {
        return $this->response;
    }
}
