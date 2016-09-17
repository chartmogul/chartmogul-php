ChartMogul\Import\Subscription
===============






* Class name: Subscription
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)



Constants
----------


### RESOURCE_PATH

    const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/subscriptions'





### ROOT_KEY

    const ROOT_KEY = 'subscriptions'





### RESOURSE_NAME

    const RESOURSE_NAME = 'Subscription'







Methods
-------


### cancel

    \ChartMogul\Import\Subscription ChartMogul\Import\Subscription::cancel(string $cancelledAt)

Cancels a subscription that was generated from an imported invoice.



* Visibility: **public**


#### Arguments
* $cancelledAt **string** - &lt;p&gt;The time at which the subscription was cancelled. Must be an ISO 8601 formatted time in the past. The timezone defaults to UTC unless otherwise specified.&lt;/p&gt;



### create

    mixed ChartMogul\Import\Subscription::create(array $data, \ChartMogul\Http\ClientInterface $client)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\Subscription::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of objects



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null** - &lt;p&gt;0&lt;/p&gt;



### __construct

    mixed ChartMogul\Resource\AbstractModel::__construct(array $data)





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $data **array**



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

    mixed ChartMogul\Resource\AbstractModel::toArray()





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)



