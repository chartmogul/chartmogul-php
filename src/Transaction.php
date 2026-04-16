<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\ExternalIdOperationsTrait;
use ChartMogul\Service\RequestService;

/**
 * Standalone Transaction resource.
 *
 * Supports both UUID-based and external-ID-based operations.
 *
 * For transactions embedded within Invoice responses, the SDK continues to use
 * Transactions\Payment and Transactions\Refund.
 */
class Transaction extends AbstractResource
{
    use GetTrait;
    use UpdateTrait;
    use DestroyTrait;
    use ExternalIdOperationsTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/transactions';

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Transaction';

    /**
     * @ignore
     */
    public const RESOURCE_ID = 'transaction_uuid';

    /**
     * @ignore
     */
    public const ROOT_KEY = 'transactions';

    protected $uuid;
    protected $external_id;
    protected $type;
    protected $date;
    protected $result;
    protected $amount_in_cents;
    protected $transaction_fees_in_cents;
    protected $transaction_fees_currency;
    protected $invoice_uuid;
    protected $disabled;
    protected $disabled_at;
    protected $disabled_by;
    protected $user_created;
    protected $errors;

    /**
     * Toggle the disabled state of a transaction by UUID.
     *
     * @param  string               $uuid
     * @param  bool                 $disabled
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function toggleDisabled(string $uuid, bool $disabled = true, ?ClientInterface $client = null): self
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH . '/:transaction_uuid/disabled_state')
            ->update(['transaction_uuid' => $uuid], ['disabled' => $disabled]);
    }
}
