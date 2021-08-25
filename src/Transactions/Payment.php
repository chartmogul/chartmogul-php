<?php

namespace ChartMogul\Transactions;

/**
* @codeCoverageIgnore
*/
class Payment extends AbstractTransaction
{
    public const RESOURCE_NAME= 'Payment Transaction';


    public $type = 'payment';
}
