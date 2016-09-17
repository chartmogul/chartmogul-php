ChartMogul\Configuration
===============






* Class name: Configuration
* Namespace: ChartMogul







Methods
-------


### __construct

    mixed ChartMogul\Configuration::__construct(string $accountToken, string $secretKey)

Creates new config object from accountToken and secretKey



* Visibility: **public**


#### Arguments
* $accountToken **string**
* $secretKey **string**



### getAccountToken

    string ChartMogul\Configuration::getAccountToken()

Get Account Token



* Visibility: **public**




### setAccountToken

    \ChartMogul\Configuration ChartMogul\Configuration::setAccountToken(string $accountToken)

Set Account Token



* Visibility: **public**


#### Arguments
* $accountToken **string**



### getSecretKey

    string ChartMogul\Configuration::getSecretKey()

Get Secret Key



* Visibility: **public**




### setSecretKey

    \ChartMogul\Configuration ChartMogul\Configuration::setSecretKey(string $secretKey)

Set Secret Key



* Visibility: **public**


#### Arguments
* $secretKey **string**



### getApiBase

    string ChartMogul\Configuration::getApiBase()

Get API Base URL



* Visibility: **public**




### getDefaultConfiguration

    \ChartMogul\Configuration ChartMogul\Configuration::getDefaultConfiguration()

Set Default Config object. Default config object is used when no config object is passed during resource call



* Visibility: **public**
* This method is **static**.




### setDefaultConfiguration

    \ChartMogul\Configuration ChartMogul\Configuration::setDefaultConfiguration(\ChartMogul\Configuration $config)

Get the default config object.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $config **[ChartMogul\Configuration](ChartMogul-Configuration.md)**


