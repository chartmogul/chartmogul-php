<?php

namespace ChartMogul\Transactions;

/**
* @codeCoverageIgnore
*/
class Payment extends AbstractTransaction
{

    const RESOURCE_NAME= 'Payment Transaction';


    public $type = 'payment';
}
