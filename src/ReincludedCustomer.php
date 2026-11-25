<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractModel;

/**
 * Result of ExcludedCustomer::reinclude().
 *
 * @property-read string $customer_uuid
 * @property-read string $status `reimporting` or `manual_reimport_required`
 */
class ReincludedCustomer extends AbstractModel
{
    protected $customer_uuid;
    protected $status;
}
