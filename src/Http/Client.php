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
    * @codeCoverageIgnore
    */
    public function getConfiguration()
    {
        return clone $this->config;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setConfiguration(Configuration $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
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
                break;
            case 401:
            case 403:
                throw new \ChartMogul\Exceptions\ForbiddenException(
                    "The requested action is forbidden.",
                    $response
                );
                break;
            case 404:
                throw new \ChartMogul\Exceptions\NotFoundException(
                    "The requested ".$this->resourceKey." could not be found.",
                    $response
                );
                break;
            case 422:
                throw new \ChartMogul\Exceptions\SchemaInvalidException(
                    "The ".$this->resourceKey." could not be created or updated.",
                    $response
                );
                break;
            case 200:
            case 201:
            case 202:
            case 204:
                break;
            default:
                throw new \ChartMogul\Exceptions\ChartMogulException(
                    $this->resourceKey." request error has occurred.",
                    $response
                );
                break;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ChartMogul\Exceptions\ChartMogulException('JSON Parse error', $response);
        }

        return $data;
    }
}
