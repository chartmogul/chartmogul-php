<?php

namespace ChartMogul;

use ChartMogul\Http\ClientInterface;
use ChartMogul\LineItems\AbstractLineItem;
use ChartMogul\LineItems\OneTime;
use ChartMogul\LineItems\Subscription as SubsItem;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Resource\AbstractResource;
use ChartMogul\Transactions\AbstractTransaction;
use ChartMogul\Transactions\Payment;
use ChartMogul\Transactions\Refund;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @property-read string $uuid
 * @property-read string $customer_uuid
 * @property-read string $external_id
 * @property-read string $data_source_uuid
 * @property-read string $customer_external_id
 * @property-read string $currency
 * @property-read string $date
 * @property-read string $due_date
 * @property-read bool $disabled
 * @property-read string $disabled_at
 * @property-read string $disabled_by
 * @property-read array $edit_history_summary
 * @property-read array $errors
 * @property-read ArrayCollection $line_items
 * @property-read ArrayCollection $transactions
 */
class Invoice extends AbstractResource
{
    use AllTrait;
    use DestroyTrait;
    use GetTrait;

    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/invoices';
    /**
     * @ignore
     */
    public const ENTRY_CLASS = Invoice::class;
    /**
     * @ignore
     */
    public const ROOT_KEY = 'invoices';

    protected $uuid;

    public $customer_uuid;
    public $external_id;
    public $data_source_uuid;
    public $customer_external_id;

    public $currency;
    public $date;
    public $due_date;

    public $disabled;
    public $disabled_at;
    public $disabled_by;
    public $edit_history_summary;
    public $errors;

    public $line_items = [];
    public $transactions = [];

    public function __construct(array $attr = [], ?ClientInterface $client = null)
    {
        parent::__construct($attr, $client);

        $this->line_items = new ArrayCollection($this->line_items);
        foreach ($this->line_items as $key => $item) {
            $this->setLineItem($key, $item);
        }

        $this->transactions = new ArrayCollection($this->transactions);
        foreach ($this->transactions as $key => $item) {
            $this->setTransaction($key, $item);
        }
    }

    protected function setLineItem($index, $line)
    {
        if ($line instanceof AbstractLineItem) {
            $this->line_items[$index] = $line;
        } elseif (is_array($line) && isset($line['type']) && $line['type'] === 'one_time') {
            $this->line_items[$index] = new OneTime($line);
        } elseif (is_array($line) && isset($line['type']) && $line['type'] === 'subscription') {
            $this->line_items[$index] = new SubsItem($line);
        }
    }

    protected function setTransaction($index, $tr)
    {
        if ($tr instanceof AbstractTransaction) {
            $this->transactions[$index] = $tr;
        } elseif (is_array($tr) && isset($tr['type']) && $tr['type'] === 'payment') {
            $this->transactions[$index] = new Payment($tr);
        } elseif (is_array($tr) && isset($tr['type']) && $tr['type'] === 'refund') {
            $this->transactions[$index] = new Refund($tr);
        }
    }
}
