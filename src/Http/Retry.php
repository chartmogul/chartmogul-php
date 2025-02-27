<?php

namespace ChartMogul\Http;

use Psr\Http\Message\ResponseInterface;
use STS\Backoff\Backoff;

class ExponentialStrategy extends \STS\Backoff\Strategies\ExponentialStrategy
{
    // min wait time
    protected $base = 1000;
}

class Retry
{
    private $retries;

    public function __construct($retries)
    {
        $this->retries = $retries;
    }

    private function retryHTTPStatus($status)
    {
        return $status == 429 || ($status >= 500 && $status < 600);
    }

    protected function shouldRetry($attempt, $maxAttempts, ?ResponseInterface $response = null, ?\Exception $ex = null)
    {
        if ($attempt >= $maxAttempts && !is_null($ex)) {
            throw  $ex;
        }

        if (!is_null($response)) {
            return $attempt < $maxAttempts && $this->retryHTTPStatus($response->getStatusCode());
        }

        // retry on all network related errors
        return $attempt < $maxAttempts && $ex instanceof \Http\Client\Exception\NetworkException;
    }

    public function retry($callback)
    {
        if ($this->retries === 0) {
            return $callback();
        }
        $backoff = new Backoff($this->retries, new ExponentialStrategy(), 60 * 1000, true);
        $backoff->setDecider($this);
        return $backoff->run($callback);
    }

    public function __invoke($attempt, $maxAttempts, $response, $exception)
    {
        return $this->shouldRetry($attempt, $maxAttempts, $response, $exception);
    }
}
