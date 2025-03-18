<?php

namespace ChartMogul\Service;

use ChartMogul\Http\Client;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ReflectionClass;
use ChartMogul\Exceptions\ChartMogulException;

class RequestService
{
    private $resourceClass;
    private $resource;
    private $resourcePath;
    private $client;

    public function __construct(?ClientInterface $client = null)
    {
        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    public function setResourceClass($resourceClass)
    {
        if (!new $resourceClass() instanceof AbstractResource) {
            throw new \Exception('Resource should be an instance of '.AbstractResource::class);
        }
        $this->resourceClass = $resourceClass;
        return $this;
    }

    public function setResource(AbstractResource $resource)
    {
        $this->resource = $resource;
        $this->resourceClass = get_class($resource);
        return $this;
    }

    /**
     * Use only when default class resource path must be overridden.
     *
     * @param  string $resourcePath
     * @return $this
     */
    public function setResourcePath($resourcePath)
    {
        $this->resourcePath = $resourcePath;
        return $this;
    }

    private function getNamedParams($path)
    {
        return array_map(
            function ($item) {
                return substr($item, 1);
            },
            array_filter(
                explode('/', $path),
                function ($part) {
                    return strpos($part, ':') === 0;
                }
            )
        );
    }

    private function applyResourcePath(&$data)
    {
        $class = $this->resourceClass;
        $path = $class::RESOURCE_PATH;
        if (isset($this->resourcePath)) {
            $path = $this->resourcePath;
        }

        foreach ($this->getNamedParams($path) as $param) {
            if (empty($data[$param])) {
                throw new \Exception('Parameter '.$param. ' is required');
            }
            $path = str_replace(':'.$param, $data[$param], $path);
            unset($data[$param]);
        }
        return $path;
    }

    private function getResourcePath($data)
    {
        $class = $this->resourceClass;
        $path = $class::RESOURCE_PATH;

        foreach ($this->getNamedParams($path) as $param) {
            if (empty($data[$param])) {
                throw new \Exception('Parameter '.$param. ' is required');
            }
            $path = str_replace(':'.$param, $data[$param], $path);
        }
        return $path;
    }

    public function create($data)
    {
        $class = $this->resourceClass;

        $obj = new $class($data, $this->client);

        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send($this->getResourcePath($data), 'POST', $obj->toArray());

        return $class::fromArray($response, $this->client);
    }

    public function update(array $id, array $data)
    {
        $class = $this->resourceClass;

        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send($this->applyResourcePath($id), 'PATCH', $data);

        return $class::fromArray($response, $this->client);
    }

    public function all($data)
    {
        $class = $this->resourceClass;
        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send($this->applyResourcePath($data), 'GET', $data);

        return $class::fromArray($response, $this->client);
    }

    /**
     * @return boolean
     */
    public function destroy()
    {
        $obj = $this->resource;

        $obj->getClient()
            ->setResourceKey($obj::RESOURCE_NAME)
            ->send($this->getResourcePath($obj->toArray()).'/'.$obj->uuid, 'DELETE');
        return true;
    }

    public function updateWithParams(array $params)
    {
        $client = $this->client;

        if (!(array_key_exists('subscription_event', $params))) {
            throw new \ChartMogul\Exceptions\SchemaInvalidException("Data is not in the good format, 'subscription_event' is missing.");
        }

        $sub_ev = $params['subscription_event'];

        if (!(array_key_exists('id', $sub_ev) || (array_key_exists('data_source_uuid', $sub_ev) && array_key_exists('external_id', $sub_ev)))) {
            throw new \ChartMogul\Exceptions\SchemaInvalidException("Param id or params external_id and data_source_uuid required.");
        }

        $class = $this->resourceClass;
        $response = $client->setResourceKey($class::RESOURCE_NAME)
            ->send($this->applyResourcePath($id), 'PATCH', $params);

        return $class::fromArray($response, $this->client);
    }

    /**
     * @return boolean
     */
    public function destroyWithParams(array $params)
    {
        $client = $this->client;

        if (!(array_key_exists('subscription_event', $params))) {
            throw new \ChartMogul\Exceptions\SchemaInvalidException("Data is not in the good format, 'subscription_event' is missing.");
        }

        $sub_ev = $params['subscription_event'];

        if (!(array_key_exists('id', $sub_ev) || (array_key_exists('data_source_uuid', $sub_ev) && array_key_exists('external_id', $sub_ev)))) {
            throw new \ChartMogul\Exceptions\SchemaInvalidException("Param id or params external_id and data_source_uuid required.");
        }

        $class = $this->resourceClass;
        $response = $client->setResourceKey($class::RESOURCE_NAME)
            ->send($this->applyResourcePath($id), 'DELETE', $params);

        return true;
    }

    public function get($uuid = null)
    {
        $class = $this->resourceClass;
        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send(
                $uuid ? $class::RESOURCE_PATH.'/'.$uuid : $class::RESOURCE_PATH,
                'GET'
            );

        return $class::fromArray($response, $this->client);
    }
}
