ChartMogul\Metrics\Subscription
===============






* Class name: Subscription
* Namespace: ChartMogul\Metrics
* Parent class: [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)





Properties
----------


### $id;

    public string $id;





* Visibility: **public**


### $plan;

    public string $plan;





* Visibility: **public**


### $quantity;

    public string $quantity;





* Visibility: **public**


### $mrr;

    public string $mrr;





* Visibility: **public**


### $arr;

    public string $arr;





* Visibility: **public**


### $status;

    public string $status;





* Visibility: **public**


### $billing_cycle;

    public string $billing_cycle;





* Visibility: **public**


### $billing_cycle_count;

    public string $billing_cycle_count;





* Visibility: **public**


### $start_date;

    public string $start_date;





* Visibility: **public**


### $end_date;

    public string $end_date;





* Visibility: **public**


### $currency;

    public string $currency;





* Visibility: **public**


### $currency_sign;

    public string $currency_sign;





* Visibility: **public**


Methods
-------


### all

    \ChartMogul\Metrics\Subscriptions ChartMogul\Metrics\Subscription::all(array $options, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of subscriptions for a given customer.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array** - &lt;p&gt;array with &lt;code&gt;customer_uuid&lt;/code&gt;&lt;/p&gt;
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### __construct

    mixed ChartMogul\Resource\AbstractModel::__construct(array $data)





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $data **array**



### toArray

    mixed ChartMogul\Resource\AbstractModel::toArray()





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)




### fromArray

    \ChartMogul\Resource\AbstractModel ChartMogul\Resource\AbstractModel::fromArray(array $data)





* Visibility: **public**
* This method is **static**.
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $data **array**


