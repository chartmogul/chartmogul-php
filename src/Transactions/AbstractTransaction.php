<?php

namespace ChartMogul\Transactions;

use ChartMogul\Resource\AbstractResource;

/**
 * @property-read string $uuid
 */
abstract class AbstractTransaction extends AbstractResource
{

    use \ChartMogul\Service\CreateTrait;

    const RESOURCE_PATH = '/v1/import/invoices/:invoice_uuid/transactions';

    protected $uuid;

    public $type;
    public $date;
    public $result;
    public $external_id;
    public $amount_in_cents;

    public $invoice_uuid;
}
