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
     * @param              array|array          $attr
     * @param              ClientInterface|null $client
     * @return             self
     */
    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        parent::__construct($attr);

        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    /**
     * @return             ClientInterface
     * @codeCoverageIgnore
     */
    public function getClient()
    {
        return clone $this->client;
    }

    /**
     * @param              ClientInterface $client
     * @return             self
     * @codeCoverageIgnore
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return Collection|static
     */
    public static function fromArray(array $data, ?ClientInterface $client = null)
    {
        if (isset($data[static::ROOT_KEY])) {
            if (static::ROOT_KEY != "subscription_events") {
                $array = new Collection(
                    array_map(
                        function ($data) use ($client) {
                            return static::fromArray($data, $client);
                        },
                        $data[static::ROOT_KEY]
                    )
                );
                // The following are subject to change soon, so they are optional.
                $array = static::allData($data, $array);
            } else {
                $array = new SubscriptionEventCollection(
                    array_map(
                        function ($data) use ($client) {
                            return static::fromArray($data, $client);
                        },
                        $data[static::ROOT_KEY]
                    )
                );

                $array = static::allMetaAndCursorData($data, $array);
            }
            return $array;
        }

        return new static($data, $client);
    }

    public static function allData(array $data, Collection $array)
    {
        if (isset($data["current_page"])) {
            $array->current_page = $data["current_page"];
        }
        if (isset($data["total_pages"])) {
            $array->total_pages = $data["total_pages"];
        }
        if (isset($data["has_more"])) {
            $array->has_more = $data["has_more"];
        }
        if (isset($data["per_page"])) {
            $array->per_page = $data["per_page"];
        }
        if (isset($data["page"])) {
            $array->page = $data["page"];
        }
        if (isset($data["cursor"])) {
            $array->cursor = $data["cursor"];
        }

        return $array;
    }

    public static function metaData(array $meta)
    {
        $array_meta = new MetaCollection();
        if (isset($meta['page'])) {
            $array_meta->page = $meta["page"];
        }
        if (isset($meta['next_key'])) {
            $array_meta->next_key = $meta["next_key"];
        }
        if (isset($meta['prev_key'])) {
            $array_meta->prev_key = $meta["prev_key"];
        }
        if (isset($meta['before_key'])) {
            $array_meta->before_key = $meta["before_key"];
        }
        if (isset($meta['total_pages'])) {
            $array_meta->total_pages = $meta["total_pages"];
        }

        return $array_meta;
    }

    public static function allMetaAndCursorData(array $data, SubscriptionEventCollection $array)
    {
        if (isset($data["meta"])) {
            $meta = $data["meta"];
            $array->meta = static::metaData($meta);
        }
        if (isset($data["cursor"])) {
            $array->cursor = $data["cursor"];
        }
        if (isset($data["has_more"])) {
            $array->has_more = $data["has_more"];
        }

        return $array;
    }
}
