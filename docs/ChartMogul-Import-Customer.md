ChartMogul\Import\Customer
===============






* Class name: Customer
* Namespace: ChartMogul\Import
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)





Properties
----------


### $external_id

    public mixed $external_id





* Visibility: **public**


### $name

    public mixed $name





* Visibility: **public**


### $email

    public mixed $email





* Visibility: **public**


### $company

    public mixed $company





* Visibility: **public**


### $country

    public mixed $country





* Visibility: **public**


### $state

    public mixed $state





* Visibility: **public**


### $city

    public mixed $city





* Visibility: **public**


### $zip

    public mixed $zip





* Visibility: **public**


### $data_source_uuid

    public mixed $data_source_uuid





* Visibility: **public**


### $uuid

    public string $uuid





* Visibility: **public**


Methods
-------


### findByExternalId

    \ChartMogul\Import\Customer ChartMogul\Import\Customer::findByExternalId(string $externalId)

Find a Customer by External ID



* Visibility: **public**
* This method is **static**.


#### Arguments
* $externalId **string**



### subscriptions

    \Doctrine\Common\Collections\ArrayCollection ChartMogul\Import\Customer::subscriptions(array $options)

Find a Customer Subscriptions



* Visibility: **public**


#### Arguments
* $options **array**



### invoices

    \Doctrine\Common\Collections\ArrayCollection ChartMogul\Import\Customer::invoices(array $options)

Find a Customer Invoices



* Visibility: **public**


#### Arguments
* $options **array**



### create

    \ChartMogul\Import\Customer ChartMogul\Import\Customer::create(array $data, \ChartMogul\Http\ClientInterface|null $client)

Create a Resource



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### all

    \Doctrine\Common\Collections\ArrayCollection|self ChartMogul\Import\Customer::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

Returns a list of objects



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null** - &lt;p&gt;0&lt;/p&gt;



### destroy

    boolean ChartMogul\Import\Customer::destroy()

Delete a resource



* Visibility: **public**




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



