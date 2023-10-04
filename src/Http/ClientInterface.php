<?php

namespace ChartMogul\Http;

use ChartMogul\Configuration;
use Psr\Http\Client\ClientInterface as HttpClient;

/**
 * @codeCoverageIgnore
 */
interface ClientInterface
{
    public function getConfiguration();

    public function setConfiguration(Configuration $config);
    public function setResourceKey($key);

    public function send($path = '', $method = 'GET', $data = []);

    public function setHttpClient(HttpClient $client);
    public function getHttpClient();
}
