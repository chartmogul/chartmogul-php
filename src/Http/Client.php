<?php

namespace ChartMogul\Http;

use ChartMogul\Configuration;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;

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
    private $apiVersion = '6.4.0';

    /**
     * @var string
     */
    private $apiBase = 'https://api.chartmogul.com';

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @param              Configuration|null           $config         Configuration Object
     * @param              HttpClient|null              $client         psr/http-client-implementaion object
     * @param              RequestFactoryInterface|null $requestFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        ?Configuration $config = null,
        ?HttpClient $client = null,
        ?RequestFactoryInterface $requestFactory = null
    ) {
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->client = $client ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
    }


    /**
     * @return             Configuration
     * @codeCoverageIgnore
     */
    public function getConfiguration()
    {
        return clone $this->config;
    }

    /**
     * @param              Configuration $config Set config
     * @codeCoverageIgnore
     */
    public function setConfiguration(Configuration $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param              HttpClient $client
     * @return             self
     * @codeCoverageIgnore
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return             HttpClient
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
            $this->config->getApiKey(). ':'. ''
        );
    }

    protected function sendWithRetry(RequestInterface $request)
    {
        $backoff = new Retry($this->config->getRetries());
        $response =  $backoff->retry(
            function () use ($request) {
                return $this->client->sendRequest($request);
            }
        );
        return $this->handleResponse($response);
    }

    public function send($path = '', $method = 'GET', $data = [])
    {
        if (isset($data['page'])) {
            throw new \ChartMogul\Exceptions\DeprecatedParameterException(
                "The 'page' query parameter is deprecated"
            );
        }

        $query = '';
        if ($method === 'GET') {
            $query = http_build_query($data);
            $data = [];
        }

        $request = $this->requestFactory
            ->createRequest($method, $this->apiBase);
        $request = $request->withUri(
            $request->getUri()->withPath($path)->withQuery($query)
        )
            ->withHeader('Authorization', $this->getBasicAuthHeader())
            ->withHeader('content-type', 'application/json')
            ->withHeader('user-agent', $this->getUserAgent());

        if (is_array($data) && !empty($data)) {
            $request->getBody()->write(json_encode($data));
        }

        return $this->sendWithRetry($request);
    }

    /**
     * @param  ResponseInterface $response
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
            case 405:
                throw new \ChartMogul\Exceptions\NotAllowedException(
                    "Method Not Allowed.",
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
                return [];
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
