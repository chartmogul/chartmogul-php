<?php

namespace ChartMogul\Import\LineItems;

/**
* @codeCoverageIgnore
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
    public $external_id;

    public $invoice_uuid;
}
