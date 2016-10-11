<?php

namespace ChartMogul\Import\Transactions;

/**
* @codeCoverageIgnore
*/
class Payment extends AbstractTransaction
{

    const RESOURCE_NAME= 'Payment Transaction';


    public $type = 'payment';
}
