<?php

namespace ChartMogul\Import\Transactions;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\noDestroyTrait;
use ChartMogul\Resource\noAllTrait;

abstract class AbstractTransaction extends AbstractResource
{

    use noDestroyTrait;
    use noAllTrait;

    const RESOURCE_PATH = '/v1/import/invoices/:invoice_uuid/transactions';

    protected $uuid;

    public $type;
    public $date;
    public $result;
    public $external_id;

    public $invoice_uuid;

    public function getResourcePath()
    {
        if (empty($this->invoice_uuid)) {
            throw new \Exception('invoice_uuid parameter missing');
        }

        return str_replace(':invoice_uuid', $this->invoice_uuid, static::RESOURCE_PATH);
    }
}
