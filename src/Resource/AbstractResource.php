<?php

namespace ChartMogul\Resource;

use ChartMogul\Http\Client;
use ChartMogul\Http\ClientInterface;

abstract class AbstractResource extends AbstractModel
{
    /**
    * @ignore
    */
    public const ROOT_KEY = null;

    /**
    * @ignore
    */
    public const RESOURCE_PATH = null;

    /**
    * @ignore
    */
    public const RESOURCE_NAME = null;


    /**
    * @var ClientInterface
    */
    private $client = null;

    /**
     * @codeCoverageIgnore
     * @param array|array $attr
     * @param ClientInterface|null $client
     * @return self
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
     * @return CollectionWithCursor|static
     */
    public static function fromArray(array $data, ClientInterface $client = null)
    {
        if (isset($data[static::ROOT_KEY])) {
            $array = new CollectionWithCursor(array_map(function ($data) use ($client) {
                return static::fromArray($data, $client);
            }, $data[static::ROOT_KEY]));

            $array->cursor = $data["cursor"];
            $array->has_more = $data["has_more"];

            return $array;
        } else {
            return new static($data, $client);
        }
    }
}
