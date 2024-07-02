<?php

namespace ChartMogul\LineItems;

/**
 * @codeCoverageIgnore
 * @property-read      string $uuid
 */
abstract class AbstractLineItem extends \ChartMogul\Resource\AbstractModel
{
    protected $uuid;

    public $external_id;

    public $account_code;
    public $amount_in_cents;
    public $discount_amount_in_cents;
    public $discount_code;
    public $discount_description;
    public $event_order;
    public $invoice_uuid;
    public $plan_uuid;
    public $quantity;
    public $tax_amount_in_cents;
    public $transaction_fees_currency;
    public $transaction_fees_in_cents;
    public $type;
}
