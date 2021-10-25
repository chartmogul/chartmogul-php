<?php

namespace ChartMogul\LineItems;

/**
* @codeCoverageIgnore
* @property-read string $uuid
*/
abstract class AbstractLineItem extends \ChartMogul\Resource\AbstractModel
{
    protected $uuid;

    public $type;
    public $amount_in_cents;
    public $quantity;
    public $discount_amount_in_cents;
    public $discount_code;
    public $tax_amount_in_cents;
    public $transaction_fees_in_cents;
    public $transaction_fees_currency;
    public $discount_description;
    public $event_order;
    public $external_id;

    public $invoice_uuid;
}
