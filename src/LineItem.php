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
 * Standalone LineItem resource.
 *
 * Supports both UUID-based and external-ID-based operations.
 *
 * For line items embedded within Invoice responses, the SDK continues to use
 * LineItems\Subscription and LineItems\OneTime.
 */
class LineItem extends AbstractResource
{
    use GetTrait;
    use UpdateTrait;
    use DestroyTrait;
    use ExternalIdOperationsTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/line_items';

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'LineItem';

    /**
     * @ignore
     */
    public const RESOURCE_ID = 'line_item_uuid';

    /**
     * @ignore
     */
    public const ROOT_KEY = 'line_items';

    protected $uuid;

    /**
     * Disable or enable a line item by UUID.
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
            ->setResourcePath(static::RESOURCE_PATH . '/:line_item_uuid/disabled_state')
            ->update(['line_item_uuid' => $uuid], ['disabled' => $disabled]);
    }

    public $external_id;
    public $type;
    public $amount_in_cents;
    public $quantity;
    public $discount_code;
    public $discount_amount_in_cents;
    public $tax_amount_in_cents;
    public $transaction_fees_in_cents;
    public $transaction_fees_currency;
    public $account_code;
    public $plan_uuid;
    public $plan_external_id;
    public $discount_description;
    public $event_order;
    public $invoice_uuid;
    public $subscription_uuid;
    public $subscription_external_id;
    public $subscription_set_external_id;
    public $service_period_start;
    public $service_period_end;
    public $prorated;
    public $proration_type;
    public $balance_transfer;
    public $disabled;
    public $disabled_at;
    public $disabled_by;
    public $user_created;
    public $errors = null;
}
