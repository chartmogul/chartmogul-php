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
    protected $external_id;
    protected $type;
    protected $amount_in_cents;
    protected $quantity;
    protected $discount_code;
    protected $discount_amount_in_cents;
    protected $tax_amount_in_cents;
    protected $transaction_fees_in_cents;
    protected $transaction_fees_currency;
    protected $account_code;
    protected $plan_uuid;
    protected $plan_external_id;
    protected $discount_description;
    protected $event_order;
    protected $invoice_uuid;
    protected $subscription_uuid;
    protected $subscription_external_id;
    protected $subscription_set_external_id;
    protected $service_period_start;
    protected $service_period_end;
    protected $prorated;
    protected $proration_type;
    protected $balance_transfer;
    protected $disabled;
    protected $disabled_at;
    protected $disabled_by;
    protected $user_created;
    protected $errors;

    /**
     * Toggle the disabled state of a line item by UUID.
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
            ->setResourcePath(static::RESOURCE_PATH . '/:line_item_uuid/disabled_state')
            ->update(['line_item_uuid' => $uuid], ['disabled' => $disabled]);
    }
}
