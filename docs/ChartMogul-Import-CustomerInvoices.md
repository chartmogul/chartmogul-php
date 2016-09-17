ChartMogul\Import\CustomerInvoices
===============






* Class name: CustomerInvoices
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)



Constants
----------


### RESOURCE_PATH

    const RESOURCE_PATH = '/v1/import/customers/:customer_uuid/invoices'





### RESOURCE_NAME

    const RESOURCE_NAME = 'Invoices'





Properties
----------


### $invoices

    public mixed $invoices = array()





* Visibility: **public**


### $customer_uuid

    public mixed $customer_uuid





* Visibility: **public**


Methods
-------


### __construct

    mixed ChartMogul\Resource\AbstractModel::__construct(array $data)





* Visibility: **public**
* This method is defined by [ChartMogul\Resource\AbstractModel](ChartMogul-Resource-AbstractModel.md)


#### Arguments
* $data **array**



### create

    mixed ChartMogul\Import\CustomerInvoices::create(array $data, \ChartMogul\Http\ClientInterface $client)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\CustomerInvoices::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of objects



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null** - &lt;p&gt;0&lt;/p&gt;



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



