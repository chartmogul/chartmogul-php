<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;

class BulkImport extends AbstractResource
{
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'BulkImport';

    protected $id;
    protected $data_source_uuid;
    protected $status;
    protected $created_at;
    protected $updated_at;
    protected $error;

    /**
     * Import data in bulk for a data source.
     *
     * @param  string               $dataSourceUuid
     * @param  array                $data  Bulk import payload (customers, plans, invoices, etc.)
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function create(string $dataSourceUuid, array $data, ?ClientInterface $client = null): self
    {
        $response = (new static([], $client))
            ->getClient()
            ->setResourceKey(static::RESOURCE_NAME)
            ->send(
                '/v1/data_sources/' . $dataSourceUuid . '/json_imports',
                'POST',
                $data
            );

        return new static($response, $client);
    }

    /**
     * Track the status of a bulk import.
     *
     * @param  string               $dataSourceUuid
     * @param  string               $importId
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function retrieve(string $dataSourceUuid, string $importId, ?ClientInterface $client = null): self
    {
        $response = (new static([], $client))
            ->getClient()
            ->setResourceKey(static::RESOURCE_NAME)
            ->send(
                '/v1/data_sources/' . $dataSourceUuid . '/json_imports/' . $importId,
                'GET'
            );

        return new static($response, $client);
    }
}
