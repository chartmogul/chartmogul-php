ChartMogul\Http\ClientInterface
===============






* Interface name: ClientInterface
* Namespace: ChartMogul\Http
* This is an **interface**






Methods
-------


### getConfiguration

    mixed ChartMogul\Http\ClientInterface::getConfiguration()





* Visibility: **public**




### setConfiguration

    mixed ChartMogul\Http\ClientInterface::setConfiguration(\ChartMogul\Configuration $config)





* Visibility: **public**


#### Arguments
* $config **[ChartMogul\Configuration](ChartMogul-Configuration.md)**



### setResourceKey

    mixed ChartMogul\Http\ClientInterface::setResourceKey($key)





* Visibility: **public**


#### Arguments
* $key **mixed**



### send

    mixed ChartMogul\Http\ClientInterface::send($path, $method, $data)





* Visibility: **public**


#### Arguments
* $path **mixed**
* $method **mixed**
* $data **mixed**



### setHttpClient

    mixed ChartMogul\Http\ClientInterface::setHttpClient(\Http\Client\HttpClient $client)





* Visibility: **public**


#### Arguments
* $client **Http\Client\HttpClient**



### getHttpClient

    mixed ChartMogul\Http\ClientInterface::getHttpClient()





* Visibility: **public**



