ChartMogul\Import\Plan
===============






* Class name: Plan
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)





Properties
----------


### $name

    public mixed $name





* Visibility: **public**


### $interval_count

    public mixed $interval_count





* Visibility: **public**


### $interval_unit

    public mixed $interval_unit





* Visibility: **public**


### $external_id

    public mixed $external_id





* Visibility: **public**


### $data_source_uuid

    public mixed $data_source_uuid





* Visibility: **public**


### $uuid

    public string $uuid





* Visibility: **public**


Methods
-------


### create

    \ChartMogul\Import\Plan ChartMogul\Import\Plan::create(array $data, \ChartMogul\Http\ClientInterface|null $client)

Create a Resource



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\Plan::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

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



