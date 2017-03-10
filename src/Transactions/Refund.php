<?php

namespace ChartMogul\Transactions;

/**
* @codeCoverageIgnore
*/
class Refund extends AbstractTransaction
{

    const RESOURCE_NAME= 'Refund Transaction';

    public $type = 'refund';
}
