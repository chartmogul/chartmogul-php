ChartMogul\Enrichment\Customer
===============






* Class name: Customer
* Namespace: ChartMogul\Enrichment
* Parent class: [ChartMogul\Resource\AbstractResource](ChartMogul-Resource-AbstractResource.md)



Constants
----------


### RESOURCE_NAME

    const RESOURCE_NAME = 'Customer'





### RESOURCE_PATH

    const RESOURCE_PATH = '/v1/customers/:customer_uuid'





Properties
----------


### $id

    public string $id





* Visibility: **public**


### $uuid

    public string $uuid





* Visibility: **public**


### $external_id

    public string $external_id





* Visibility: **public**


### $name

    public string $name





* Visibility: **public**


### $email

    public string $email





* Visibility: **public**


### $status

    public string $status





* Visibility: **public**


### $customer_since

    public string $customer_since





* Visibility: **public**


### $attributes

    public string $attributes





* Visibility: **public**


### $address

    public string $address





* Visibility: **public**


### $mrr

    public string $mrr





* Visibility: **public**


### $arr

    public string $arr





* Visibility: **public**


### $billing_system_url

    public string $billing_system_url





* Visibility: **public**


### $chartmogul_url

    public string $chartmogul_url





* Visibility: **public**


### $billing_system_type

    public string $billing_system_type





* Visibility: **public**


### $currency

    public string $currency





* Visibility: **public**


### $currency_sign

    public string $currency_sign





* Visibility: **public**


Methods
-------


### tags

    array ChartMogul\Enrichment\Customer::tags()

Get Customer Tages



* Visibility: **public**




### customAttributes

    array ChartMogul\Enrichment\Customer::customAttributes()

Get Customer Custom Attributes



* Visibility: **public**




### retrieve

    \ChartMogul\Enrichment\Customer ChartMogul\Enrichment\Customer::retrieve(string $customer_uuid, \ChartMogul\Http\ClientInterface|null $client)

Retrieve a Customer



* Visibility: **public**
* This method is **static**.


#### Arguments
* $customer_uuid **string**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### all

    \ChartMogul\Enrichment\Customers ChartMogul\Enrichment\Customer::all(array $data, \ChartMogul\Http\ClientInterface|null $client)

List all Customers



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### search

    \ChartMogul\Enrichment\Customers ChartMogul\Enrichment\Customer::search(string $email, \ChartMogul\Http\ClientInterface|null $client)

Search for Customers



* Visibility: **public**
* This method is **static**.


#### Arguments
* $email **string**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### merge

    boolean ChartMogul\Enrichment\Customer::merge(array $from, array $into, \ChartMogul\Http\ClientInterface|null $client)

Merge Customers



* Visibility: **public**
* This method is **static**.


#### Arguments
* $from **array**
* $into **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### addTags

    mixed ChartMogul\Enrichment\Customer::addTags($tags)

Add tags to a customer



* Visibility: **public**


#### Arguments
* $tags **mixed**



### removeTags

    mixed ChartMogul\Enrichment\Customer::removeTags($tags)

Remove Tags from a Customer



* Visibility: **public**


#### Arguments
* $tags **mixed**



### addCustomAttributes

    mixed ChartMogul\Enrichment\Customer::addCustomAttributes($custom)

Add Custom Attributes to a Customer



* Visibility: **public**


#### Arguments
* $custom **mixed**



### removeCustomAttributes

    mixed ChartMogul\Enrichment\Customer::removeCustomAttributes($custom)

Remove Custom Attributes from a Customer



* Visibility: **public**


#### Arguments
* $custom **mixed**



### updateCustomAttributes

    mixed ChartMogul\Enrichment\Customer::updateCustomAttributes($custom)

Update Custom Attributes of a Customer



* Visibility: **public**


#### Arguments
* $custom **mixed**



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



