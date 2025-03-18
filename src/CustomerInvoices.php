<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Http\ClientInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CustomerInvoices extends AbstractResource
{
    use \ChartMogul\Service\CreateTrait;
    use \ChartMogul\Service\AllTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/invoices';
    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Invoices';

    public $invoices = [];

    public $customer_uuid;

    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        parent::__construct($attr, $client);

        $this->invoices = new ArrayCollection($this->invoices);
        foreach ($this->invoices as $key => $item) {
            $this->setInvoice($key, $item);
        }
    }

    public static function destroyAll($dataSourceUUID, $customerUUID, ?ClientInterface $client = null)
    {
        (new static([], $client))
            ->getClient()
            ->setResourcekey(static::class)
            ->send('/v1/data_sources/'.$dataSourceUUID.'/customers/'.$customerUUID.'/invoices', 'DELETE');
        return true;
    }

    protected function setInvoice($index, $invoice)
    {
        if ($invoice instanceof \ChartMogul\Invoice) {
            $this->invoices[$index] = $invoice;
        } elseif (is_array($invoice)) {
            $this->invoices[$index] = new \ChartMogul\Invoice($invoice);
        }
    }
}
