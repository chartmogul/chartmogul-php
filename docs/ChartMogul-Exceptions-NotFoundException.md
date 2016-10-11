ChartMogul\Exceptions\NotFoundException
===============

NotFoundException




* Class name: NotFoundException
* Namespace: ChartMogul\Exceptions
* Parent class: [ChartMogul\Exceptions\ChartMogulException](ChartMogul-Exceptions-ChartMogulException.md)







Methods
-------


### __construct

    mixed ChartMogul\Exceptions\ChartMogulException::__construct(string $message, \Psr\Http\Message\ResponseInterface|null $response, \Exception|null $previous)

ChartMogulException



* Visibility: **public**
* This method is defined by [ChartMogul\Exceptions\ChartMogulException](ChartMogul-Exceptions-ChartMogulException.md)


#### Arguments
* $message **string** - &lt;p&gt;Exception message&lt;/p&gt;
* $response **Psr\Http\Message\ResponseInterface|null** - &lt;p&gt;ResponseInterface object&lt;/p&gt;
* $previous **Exception|null**



### getStatusCode

    integer ChartMogul\Exceptions\ResponseException::getStatusCode()

GET HTTP Status Code



* Visibility: **public**
* This method is defined by [ChartMogul\Exceptions\ResponseException](ChartMogul-Exceptions-ResponseException.md)




### getResponse

    string|array ChartMogul\Exceptions\ResponseException::getResponse()

Get HTTP response



* Visibility: **public**
* This method is defined by [ChartMogul\Exceptions\ResponseException](ChartMogul-Exceptions-ResponseException.md)



