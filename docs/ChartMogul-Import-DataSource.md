ChartMogul\Import\DataSource
===============






* Class name: DataSource
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)





Properties
----------


### $name

    public mixed $name





* Visibility: **public**


Methods
-------


### create

    mixed ChartMogul\Import\DataSource::create(array $data, \ChartMogul\Http\ClientInterface $client)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\DataSource::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of objects



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null** - &lt;p&gt;0&lt;/p&gt;



### destroy

    mixed ChartMogul\Import\DataSource::destroy()





* Visibility: **public**




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



