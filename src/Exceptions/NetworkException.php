<?php

namespace ChartMogul\Exceptions;

/**
 * Network-related exceptions for ChartMogul API requests.
 *
 * Thrown when network requests fail, timeout, or return null responses.
 */
class NetworkException extends ChartMogulException
{
    /**
     * NetworkException constructor.
     *
     * @param string $message Error message describing the network failure
     * @param \Psr\Http\Message\ResponseInterface|null $response HTTP response if available
     * @param \Throwable|null $previous Previous exception for chaining
     */
    public function __construct(string $message, ?\Psr\Http\Message\ResponseInterface $response = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, $response, $previous);
    }
}
