ChartMogul\Import\Subscription
===============






* Class name: Subscription
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)



Constants
----------


### RESOURCE_NAME

    const RESOURCE_NAME = 'Subscription'





Properties
----------


### $uuid

    public string $uuid





* Visibility: **public**


### $external_id

    public string $external_id





* Visibility: **public**


### $cancellation_dates

    public string $cancellation_dates





* Visibility: **public**


### $plan_uuid

    public string $plan_uuid





* Visibility: **public**


### $data_source_uuid

    public string $data_source_uuid





* Visibility: **public**


Methods
-------


### cancel

    \ChartMogul\Import\Subscription ChartMogul\Import\Subscription::cancel(string $cancelledAt)

Cancels a subscription that was generated from an imported invoice.



* Visibility: **public**


#### Arguments
* $cancelledAt **string** - &lt;p&gt;The time at which the subscription was cancelled.&lt;/p&gt;



### create

    \ChartMogul\Import\Subscription ChartMogul\Import\Subscription::create(array $data, \ChartMogul\Http\ClientInterface|null $client)

Create a Resource



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\Subscription::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of objects



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null** - &lt;p&gt;0&lt;/p&gt;



### __construct

    mixed ChartMogul\Resource\AbstractModel::__construct(array $attributes)





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $attributes **array**



### getClient

    \ChartMogul\Http\ClientInterface ChartMogul\Resource\AbstractResource::getClient()





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)




### setClient

    \ChartMogul\Resource\AbstractResource ChartMogul\Resource\AbstractResource::setClient(\ChartMogul\Http\ClientInterface $client)





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)


#### Arguments
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)**



### fromArray

    \ChartMogul\Resource\AbstractModel ChartMogul\Resource\AbstractModel::fromArray(array $data)





* Visibility: **public**
* This method is **static**.
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $data **array**



### toArray

    array ChartMogul\Resource\AbstractModel::toArray()





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)



