ChartMogul\Metrics\Activity
===============






* Class name: Activity
* Namespace: ChartMogul\Metrics
* Parent class: [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)





Properties
----------


### $id;

    public string $id;





* Visibility: **public**


### $description;

    public string $description;





* Visibility: **public**


### $type;

    public string $type;





* Visibility: **public**


### $date;

    public string $date;





* Visibility: **public**


### $activity_arr;

    public string $activity_arr;





* Visibility: **public**


### $activity_mrr;

    public string $activity_mrr;





* Visibility: **public**


### $activity_mrr_movement;

    public string $activity_mrr_movement;





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

    \ChartMogul\Metrics\Activities ChartMogul\Metrics\Activity::all(array $options, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of activities for a given customer.



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


