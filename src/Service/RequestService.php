<?php
namespace ChartMogul\Service;

use ChartMogul\Http\Client;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ReflectionClass;

class RequestService
{

    private $resourceClass;

    private $client;

    public function __construct(ClientInterface $client = null)
    {
        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    public function setResourceClass($resourceClass)
    {
        if (!new $resourceClass instanceof AbstractResource) {
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

    private function getNamedParams($path)
    {
        return array_map(function ($item) {
            return substr($item, 1);
        }, array_filter(explode('/', $path), function ($part) {
            return strpos($part, ':') === 0;
        }));
    }

    private function applyResourcePath(&$data)
    {
        $class = $this->resourceClass;
        $path = $class::RESOURCE_PATH;
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
    
    public function get($uuid)
    {
        $class = $this->resourceClass;
        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send($class::RESOURCE_PATH.'/'.$uuid, 'GET');

        return $class::fromArray($response, $this->client);
    }
    
    public function update($uuid, $data)
    {
        $class = $this->resourceClass;

        $response = $this->client
            ->setResourceKey($class::RESOURCE_NAME)
            ->send($class::RESOURCE_PATH.'/'.$uuid, 'PATCH', $data);
        return $class::fromArray($response, $this->client);
    }
}
