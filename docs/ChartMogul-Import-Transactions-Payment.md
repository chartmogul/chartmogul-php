ChartMogul\Import\Transactions\Payment
===============






* Class name: Payment
* Namespace: ChartMogul\Import\Transactions
* Parent class: [ChartMogul\Import\Transactions\AbstractTransaction](ChartMogul-Import-Transactions-AbstractTransaction.md)



Constants
----------


### RESOURCE_NAME

    const RESOURCE_NAME = 'Payment Transaction'





### RESOURCE_PATH

    const RESOURCE_PATH = '/v1/import/invoices/:invoice_uuid/transactions'





Properties
----------


### $type

    public mixed $type





* Visibility: **public**


### $date

    public mixed $date





* Visibility: **public**


### $result

    public mixed $result





* Visibility: **public**


### $external_id

    public mixed $external_id





* Visibility: **public**


### $invoice_uuid

    public mixed $invoice_uuid





* Visibility: **public**


### $uuid

    public string $uuid





* Visibility: **public**
* This property is defined by [ChartMogul\Import\Transactions\AbstractTransaction](ChartMogul-Import-Transactions-AbstractTransaction.md)


Methods
-------


### create

    \ChartMogul\Import\Transactions\AbstractTransaction ChartMogul\Import\Transactions\AbstractTransaction::create(array $data, \ChartMogul\Http\ClientInterface|null $client)

Create a Resource



* Visibility: **public**
* This method is **static**.
* This method is defined by [ChartMogul\Import\Transactions\AbstractTransaction](ChartMogul-Import-Transactions-AbstractTransaction.md)


#### Arguments
* $data **array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



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



