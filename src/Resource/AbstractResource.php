<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\Client;
use ChartMogul\Http\ClientInterface;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractResource extends AbstractModel
{


    /**
    * @ignore
    */
    const ROOT_KEY = null;

    /**
    * @ignore
    */
    const RESOURCE_PATH = null;

    /**
    * @ignore
    */
    const RESOURCE_NAME = null;


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
     * @param array $data
     * @param ClientInterface|null $client
     * @return ArrayCollection|self
     */
    public static function fromArray(array $data, ClientInterface $client = null)
    {
        if (isset($data[static::ROOT_KEY])) {
            return new ArrayCollection(array_map([static::class, 'fromArray'], $data[static::ROOT_KEY]));
        }

        return new static($data, $client);
    }
}
