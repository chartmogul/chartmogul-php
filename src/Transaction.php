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

    /**
     * Disable or enable a transaction by UUID.
     *
     * @param  string               $uuid
     * @param  bool                 $disabled
     * @param  ClientInterface|null $client
     * @return self
     */
    public static function disable(string $uuid, bool $disabled = true, ?ClientInterface $client = null): self
    {
        return (new RequestService($client))
            ->setResourceClass(static::class)
            ->setResourcePath(static::RESOURCE_PATH . '/:transaction_uuid/disabled_state')
            ->update(['transaction_uuid' => $uuid], ['disabled' => $disabled]);
    }

    public $external_id;
    public $type;
    public $date;
    public $result;
    public $amount_in_cents;
    public $transaction_fees_in_cents;
    public $transaction_fees_currency;
    public $invoice_uuid;
    public $disabled;
    public $disabled_at;
    public $disabled_by;
    public $user_created;
    public $errors = null;
}
