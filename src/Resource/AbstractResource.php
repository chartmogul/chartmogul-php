<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\Client;
use ChartMogul\Http\ClientInterface;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractResource extends AbstractModel
{


    const ROOT_KEY = null;

    /**
    * @var Client
    */
    private $client = null;

    /**
     * @codeCoverageIgnore
     * @param array|array $attr
     * @param ClientInterface|null $client
     * @return type
     */
    public function __construct(array $attr = [], ClientInterface $client = null)
    {

        parent::__construct($attr);

        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    /**
     * Get ROOT_KEY
     * @return string
     * @codeCoverageIgnore
     */
    public function getRootKey()
    {
        return static::ROOT_KEY;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getResourcePath()
    {
        return static::RESOURCE_PATH;
    }

    /**
     * @return ClientInterface
     * @codeCoverageIgnore
     */
    public function getClient()
    {
        return clone $this->client;
    }

    /**
     * @param ClientInterface $client
     * @return self
     * @codeCoverageIgnore
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public static function getResourceName()
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    /**
     * @param array|array &$data
     * @return self
     * @codeCoverageIgnore
     */
    protected function applyAttributes(array &$data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
                unset($data[$key]);
            }
        }
        return $this;
    }

    /**
     * @param array|array $data
     * @param ClientInterface|null $client
     * @return self|ArrayCollection
     */
    public static function all(array $data = [], ClientInterface $client = null)
    {

        $obj = (new static([], $client))->applyAttributes($data);

        $response = $obj->client
            ->setResourceKey(self::getResourceName())
            ->send($obj->getResourcePath(), 'GET', $data);

        return static::fromArray($response, $client);
    }

    /**
     * @param array|array $data
     * @param ClientInterface|null $client
     * @return self
     */
    public static function create(array $data = [], ClientInterface $client = null)
    {
        $obj = new static($data, $client);
        $response = $obj->client
            ->setResourceKey(self::getResourceName())
            ->send($obj->getResourcePath(), 'POST', $obj->toArray());

        return static::fromArray($response, $client);
    }

    /**
     * @return boolean
     */
    public function destroy()
    {
        $this->custom($this->getResourcePath().'/'.$this->uuid, 'DELETE');
        return true;
    }

    /**
     * @param type $uri
     * @param type|string $method
     * @param array|array $data
     * @return Zend\Diactoros\Response
     */
    protected function custom($uri, $method = 'GET', array $data = [])
    {
        return $this->client
            ->setResourceKey($this->getRootKey())
            ->send($uri, $method, $data);
    }

    /**
     * @param array $data
     * @param ClientInterface|null $client
     * @return self
     */
    public static function fromArray(array $data, ClientInterface $client = null)
    {
        if (isset($data[static::ROOT_KEY])) {
            return new ArrayCollection(array_map([static::class, 'fromArray'], $data[static::ROOT_KEY]));
        }

        return new static($data, $client);
    }
}
