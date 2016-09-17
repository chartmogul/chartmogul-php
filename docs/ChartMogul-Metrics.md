ChartMogul\Metrics
===============

The Metrics API allows users to programmatically pull the subscription metrics that ChartMogul generates.




* Class name: Metrics
* Namespace: ChartMogul







Methods
-------


### all

    \ChartMogul\Metrics\AllKeyMetrics ChartMogul\Metrics::all(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves all key metrics, for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### arpa

    \ChartMogul\Metrics\ARPAs ChartMogul\Metrics::arpa(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Average Revenue Per Account (ARPA), for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### arr

    \ChartMogul\Metrics\ARRs ChartMogul\Metrics::arr(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Annualized Run Rate (ARR), for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### asp

    \ChartMogul\Metrics\ASPs ChartMogul\Metrics::asp(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Average Sale Price (ASP), for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### customerChurnRate

    \ChartMogul\Metrics\CustomerChurnRates ChartMogul\Metrics::customerChurnRate(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Customer Churn Rate, for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### customerCount

    \ChartMogul\Metrics\CustomerCounts ChartMogul\Metrics::customerCount(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the number of active customers, for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### mrr

    \ChartMogul\Metrics\MRRs ChartMogul\Metrics::mrr(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Monthly Recurring Revenue (MRR), for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### ltv

    \ChartMogul\Metrics\LTVs ChartMogul\Metrics::ltv(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Customer Lifetime Value (LTV), for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**



### mrrChurnRate

    \ChartMogul\Metrics\MRRChurnRates ChartMogul\Metrics::mrrChurnRate(array|array $options, \ChartMogul\Http\ClientInterface|null $client)

Retrieves the Net MRR Churn Rate, for the specified time period.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $options **array|array**
* $client **[ChartMogul\Http\ClientInterface](ChartMogul-Http-ClientInterface.md)|null**


