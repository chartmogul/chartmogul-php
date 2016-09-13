<?php

namespace ChartMogul\Import;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\noDestroyTrait;
use ChartMogul\Http\ClientInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CustomerInvoices extends AbstractResource
{

    use noDestroyTrait;

    const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/invoices';

    public $invoices = [];

    public $customer_uuid;

    public function __construct(array $attr = [], ClientInterface $client = null)
    {
        parent::__construct($attr, $client);

        $this->invoices = new ArrayCollection($this->invoices);
        foreach ($this->invoices as $key => $item) {
            $this->setInvoice($key, $item);
        }
    }

    public function getResourcePath()
    {
        if (empty($this->customer_uuid)) {
            throw new \Exception('customer_uuid parameter missing');
        }

        return str_replace(':customer_uuid', $this->customer_uuid, static::RESOURCE_PATH);
    }

    public function setInvoice($index, $invoice)
    {

        if ($invoice instanceof \ChartMogul\Import\Invoice) {
            $this->invoices[$index] =$invoice;
        } else {
            $this->invoices[$index] = new \ChartMogul\Import\Invoice($invoice);
        }
    }
}
