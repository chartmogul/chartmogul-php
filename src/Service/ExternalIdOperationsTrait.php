<?php

namespace ChartMogul\Service;

use ChartMogul\Http\ClientInterface;

/**
 * Shared static methods for resources that support CRUD by data_source_uuid + external_id.
 * These resources use query-parameter-based lookups (not path-based).
 */
trait ExternalIdOperationsTrait
{
    /**
     * Retrieve a resource by data_source_uuid and external_id.
     *
     * @param  string               $dataSourceUuid
     * @param  string               $externalId
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function retrieveByExternalId(
        string $dataSourceUuid,
        string $externalId,
        ?ClientInterface $client = null
    ): self {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->getByExternalId([
                'data_source_uuid' => $dataSourceUuid,
                'external_id' => $externalId,
            ]);
    }

    /**
     * Update a resource by data_source_uuid and external_id.
     *
     * @param  string               $dataSourceUuid
     * @param  string               $externalId
     * @param  array                $data
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function updateByExternalId(
        string $dataSourceUuid,
        string $externalId,
        array $data,
        ?ClientInterface $client = null
    ): self {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->updateByExternalId(
                ['data_source_uuid' => $dataSourceUuid, 'external_id' => $externalId],
                $data
            );
    }

    /**
     * Delete a resource by data_source_uuid and external_id.
     *
     * @param  string               $dataSourceUuid
     * @param  string               $externalId
     * @param  ClientInterface|null $client
     * @return boolean
     */
    public static function destroyByExternalId(
        string $dataSourceUuid,
        string $externalId,
        ?ClientInterface $client = null
    ): bool {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->destroyByExternalId([
                'data_source_uuid' => $dataSourceUuid,
                'external_id' => $externalId,
            ]);
    }

    /**
     * Toggle disabled state of a resource by data_source_uuid and external_id.
     *
     * @param  string               $dataSourceUuid
     * @param  string               $externalId
     * @param  bool                 $disabled
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function toggleDisabledByExternalId(
        string $dataSourceUuid,
        string $externalId,
        bool $disabled,
        ?ClientInterface $client = null
    ): self {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->patchSubresourceByExternalId(
                'disabled_state',
                ['data_source_uuid' => $dataSourceUuid, 'external_id' => $externalId],
                ['disabled' => $disabled]
            );
    }
}
