ChartMogul\Http\Client
===============






* Class name: Client
* Namespace: ChartMogul\Http
* This class implements: [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)






Methods
-------


### __construct

    mixed ChartMogul\Http\Client::__construct(\ChartMogul\Configuration|null $config, \Http\Client\HttpClient|null $client)





* Visibility: **public**


#### Arguments
* $config **[ChartMogul\Configuration](ChartMogul-Configuration.md)|null** - &lt;p&gt;Configuration Object&lt;/p&gt;
* $client **Http\Client\HttpClient|null** - &lt;p&gt;php-http/client-implementaion object&lt;/p&gt;



### getConfiguration

    mixed ChartMogul\Http\ClientInterface::getConfiguration()





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)




### setConfiguration

    mixed ChartMogul\Http\ClientInterface::setConfiguration(\ChartMogul\Configuration $config)





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)


#### Arguments
* $config **[ChartMogul\Configuration](ChartMogul-Configuration.md)**



### setHttpClient

    mixed ChartMogul\Http\ClientInterface::setHttpClient(\Http\Client\HttpClient $client)





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)


#### Arguments
* $client **Http\Client\HttpClient**



### getHttpClient

    mixed ChartMogul\Http\ClientInterface::getHttpClient()





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)




### setResourceKey

    mixed ChartMogul\Http\ClientInterface::setResourceKey($key)





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)


#### Arguments
* $key **mixed**



### getResourceKey

    mixed ChartMogul\Http\Client::getResourceKey()





* Visibility: **public**




### getApiVersion

    mixed ChartMogul\Http\Client::getApiVersion()





* Visibility: **public**




### getApiBase

    mixed ChartMogul\Http\Client::getApiBase()





* Visibility: **public**




### send

    mixed ChartMogul\Http\ClientInterface::send($path, $method, $data)





* Visibility: **public**
* This method is defined by [ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)


#### Arguments
* $path **mixed**
* $method **mixed**
* $data **mixed**


