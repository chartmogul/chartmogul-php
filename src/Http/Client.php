<?php

namespace ChartMogul\Http;

use ChartMogul\Configuration;
use Psr\Http\Message\ResponseInterface;
use Http\Client\HttpClient;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class Client implements ClientInterface
{

    /**
    * @var HttpClient
    */
    private $client;

    /**
    * @var Configuration
    */
    private $config;

    /**
    * @var string
    */
    private $resourceKey = 'resource';

    /**
    * @var string
    */
    private $apiVersion = '1.0.0';

    /**
    * @var string
    */
    private $apiBase = 'https://api.chartmogul.com';

    /**
     * @param Configuration|null $config Configuration Object
     * @param HttpClient|null $client php-http/client-implementaion object
     * @codeCoverageIgnore
     */
    public function __construct(Configuration $config = null, HttpClient $client = null)
    {
        if (is_null($config)) {
            $config = Configuration::getDefaultConfiguration();
        }
        $this->config = $config;

        if (is_null($client)) {
            $client = new GuzzleClient();
        }
        $this->client = $client;
    }


    /**
     * @return Configuration
     * @codeCoverageIgnore
     */
    public function getConfiguration()
    {
        return clone $this->config;
    }

    /**
     * @param Configuration $config Set config
     * @codeCoverageIgnore
     */
    public function setConfiguration(Configuration $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param HttpClient $client
     * @return self
     * @codeCoverageIgnore
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return HttpClient
     * @codeCoverageIgnore
     */
    public function getHttpClient()
    {
        return clone $this->client;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setResourceKey($key)
    {
        $this->resourceKey = $key;
        return $this;
    }

    /**
    * @codeCoverageIgnore
    */
    public function getResourceKey()
    {
        return $this->resourceKey;
    }

    /**
    * @codeCoverageIgnore
    */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
    * @codeCoverageIgnore
    */
    public function getApiBase()
    {
        return $this->apiBase;
    }

    public function getUserAgent()
    {
        return implode(
            '/',
            [
            'chartmogul-php',
            $this->apiVersion,
            'PHP-'. implode('.', [PHP_MAJOR_VERSION, PHP_MINOR_VERSION])
            ]
        );
    }

    public function getBasicAuthHeader()
    {
        return 'Basic '. base64_encode(
            $this->config->getAccountToken(). ':'. $this->config->getSecretKey()
        );
    }

    public function send($path = '', $method = 'GET', $data = [])
    {

        $query = '';
        if ($method === 'GET') {
            $query = http_build_query($data);
            $data = [];
        }

        $uri = (new Uri($this->apiBase))
            ->withQuery($query)
            ->withPath($path);

        $request = (new Request())
            ->withUri($uri)
            ->withMethod($method)
            ->withHeader('Authorization', $this->getBasicAuthHeader())
            ->withHeader('content-type', 'application/json')
            ->withHeader('user-agent', $this->getUserAgent());

        if (is_array($data) && !empty($data)) {
            $request->getBody()->write(json_encode($data));
        }

        $response = $this->client->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @throws \ChartMogul\Exceptions\ChartMogulException
     * @return array
     */
    public function handleResponse(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $data = json_decode($response->getBody()->getContents(), true);
        switch ($response->getStatusCode()) {
            case 400:
                throw new \ChartMogul\Exceptions\SchemaInvalidException(
                    "JSON schema validation hasn't passed.",
                    $response
                );
            case 401:
            case 403:
                throw new \ChartMogul\Exceptions\ForbiddenException(
                    "The requested action is forbidden.",
                    $response
                );
            case 404:
                throw new \ChartMogul\Exceptions\NotFoundException(
                    "The resource could not be found.",
                    $response
                );
            case 422:
                throw new \ChartMogul\Exceptions\SchemaInvalidException(
                    "The ".$this->resourceKey." could not be created or updated.",
                    $response
                );
            case 200:
            case 201:
            case 202:
                break;
            case 204: // HTTP No Content
                return "";
            default:
                throw new \ChartMogul\Exceptions\ChartMogulException(
                    $this->resourceKey." request error has occurred.",
                    $response
                );
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ChartMogul\Exceptions\ChartMogulException('JSON Parse error', $response);
        }

        return $data;
    }
}
