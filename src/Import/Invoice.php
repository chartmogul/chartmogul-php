<?php

namespace ChartMogul\Import;

use ChartMogul\Import\LineItems\AbstractLineItem;
use ChartMogul\Import\LineItems\OneTime;
use ChartMogul\Import\LineItems\Subscription;
use ChartMogul\Import\Transactions\AbstractTransaction;
use ChartMogul\Import\Transactions\Payment;
use ChartMogul\Import\Transactions\Refund;
use Doctrine\Common\Collections\ArrayCollection;

class Invoice extends \ChartMogul\Resource\AbstractModel
{

    protected $uuid;

    public $date;
    public $currency;
    public $line_items = [];
    public $transactions = [];
    public $external_id;
    public $due_date;

    public function __construct(array $attr = [])
    {
        parent::__construct($attr);

        $this->line_items = new ArrayCollection($this->line_items);
        foreach ($this->line_items as $key => $item) {
            $this->setLineItem($key, $item);
        }

        $this->transactions = new ArrayCollection($this->transactions);
        foreach ($this->transactions as $key => $item) {
            $this->setTransaction($key, $item);
        }
    }

    public function setLineItem($index, $line)
    {

        if ($line instanceof AbstractLineItem) {
            $this->line_items[$index] = $line;
        } elseif (is_array($line) && isset($line['type']) && $line['type'] === 'one_time') {
            $this->line_items[$index] = new OneTime($line);
        } elseif (is_array($line) && isset($line['type']) && $line['type'] === 'subscription') {
            $this->line_items[$index] = new Subscription($line);
        }
    }

    public function setTransaction($index, $tr)
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
