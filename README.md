<p align="center">
<a href="https://chartmogul.com"><img width="200" src="https://chartmogul.com/assets/img/logo.png"></a>
</p>

<h3 align="center">Official ChartMogul API PHP Client</h3>

<p align="center"><code>chartmogul-php</code> provides convenient PHP bindings for <a href="https://dev.chartmogul.com">ChartMogul's API</a>.</p>
<p align="center">
  <a href="https://packagist.org/packages/chartmogul/chartmogul-php"><img src="https://badge.fury.io/ph/chartmogul/chartmogul-php.svg" alt="PHP Package" /></a>
  <a href="https://travis-ci.org/chartmogul/chartmogul-php"><img src="https://travis-ci.org/chartmogul/chartmogul-php.svg?branch=master" alt="Build Status"/></a>
</p>
<hr>

<p align="center">
<b><a href="#installation">Installation</a></b>
|
<b><a href="#configuration">Configuration</a></b>
|
<b><a href="#usage">Usage</a></b>
|
<b><a href="#development">Development</a></b>
|
<b><a href="#contributing">Contributing</a></b>
|
<b><a href="#license">License</a></b>
</p>
<hr>
<br>

## Installation

This library requires php 5.4 or above.


**Using Composer**:


```sh
composer require chartmogul/chartmogul-php@dev-master
```
or in `composer.json`:

```json
{
    "require": {
        "chartmogul/chartmogul-phpt": "dev-master"
    }
}
```

## Configuration

Setup the default configuration with your ChartMogul account token and secret key:

```php
require('./vendor/autoload.php');

ChartMogul\Configuration::getDefaultConfiguration()
  ->setAccountToken('<YOUR_ACCOUNT_TOKEN>')
    ->setSecretKey('<YOUR_SECRET_KEY>');
```

## Usage

```php
try {
  $dataSources = ChartMogul\Import\DataSource::all();
} catch(\ChartMogul\Exceptions\ForbiddenException $e){
  // handle authentication exception
} catch(\ChartMogul\Exceptions\ChartMogulException $e){
    echo $e->getResponse();
    echo $e->getStatusCode();
}

$ds = $dataSources->first();
var_dump($ds->uuid); // access Datasource properties
$dsAsArray = $ds->toArray(); // convert to array
$ds->destroy();

```

All array results are wrapped with `Doctrine\Common\Collections\ArrayCollection` for ease of access.

### Import API

Available methods in Import API:

#### [Data Sources](https://dev.chartmogul.com/docs/data-sources)

**Create Datasources**

```php
$ds = ChartMogul\Import\DataSource::create([
  'name' => 'In-house Billing'
]);
```

**List Datasources**

```php
ChartMogul\Import\DataSource::all();
```

**Delete a Datasource**

```php
$dataSources = ChartMogul\Import\DataSource::all();
$ds = $dataSources>last();
$ds->destroy();
```

#### Customers

**Import a Customer**

```php
ChartMogul\Import\Customer::create([
    "data_source_uuid" => $ds->uuid,
    "external_id" => "cus_0003",
    "name" => "Adam Smith",
    "email" => "adam@smith.com",
    "country" => "US",
    "city" => "New York"
]);
```

**List Customers**

```php
ChartMogul\Import\Customer::all([
  'page' => 1,
  'data_source_uuid' => $ds->uuid
]);
```

**Delete A Customer**

```php
$cus = ChartMogul\Import\Customer::all()->last();
$cus->destroy();
```

#### Plans

**Import a Plan**

```php
ChartMogul\Import\Plan::create([
    "data_source_uuid" => $ds->uuid,
    "name" => "Bronze Plan",
    "interval_count" => 1,
    "interval_unit" => "month",
    "external_id" => "plan_0001"
]);
```

**List Plans**

```php
$plans = ChartMogul\Import\Plan::all([
  'page' => 1
]);
```


#### Invoices

**Import Invoices**

```php
$plan = ChartMogul\Import\Plan::all()->first();
$cus = ChartMogul\Import\Customer::all()->first();

$line_itme_1 = new ChartMogul\Import\LineItems\Subscription([
    'subscription_external_id' => "sub_0001",
    'plan_uuid' =>  $plan->uuid,
    'service_period_start' =>  "2015-11-01 00:00:00",
    'service_period_end' =>  "2015-12-01 00:00:00",
    'amount_in_cents' => 5000,
    'quantity' => 1,
    'discount_code' => "PSO86",
    'discount_amount_in_cents' => 1000,
    'tax_amount_in_cents' => 900
]);

$line_itme_2 = new ChartMogul\Import\LineItems\OneTime([
    "description" => "Setup Fees",
    "amount_in_cents" => 2500,
    "quantity" => 1,
    "discount_code" => "PSO86",
    "discount_amount_in_cents" => 500,
    "tax_amount_in_cents" => 450
]);

$transaction = new ChartMogul\Import\Transactions\Payment([
    "date" => "2015-11-05T00:14:23.000Z",
    "result" => "successful"
]);

$invoice = new ChartMogul\Import\Invoice([
    'external_id' => 'INV0001',
    'date' =>  "2015-11-01 00:00:00",
    'currency' => 'USD',
    'due_date' => "2015-11-15 00:00:00",
    'line_items' => [$line_itme_1, $line_itme_2],
    'transactions' => [$transaction]
]);

$ci = ChartMogul\Import\CustomerInvoices::create([
    'customer_uuid' => $cus->uuid,
    'invoices' => [$invoice]
]);

```

**List Customer Invoices**

```php
$ci = ChartMogul\Import\CustomerInvoices::all([
    'customer_uuid' => $cus->uuid,
    'page' => 1,
    'per_page' => 200
]);
```

### Metrics


**Retrieve all key metrics**

```php
ChartMogul\Metrics::all([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month',
    'geo' => 'GB',
    'plans' => $plan->name
]);
```

**Retrieve MRR**


```php
ChartMogul\Metrics::mrr([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month',
    'geo' => 'GB',
    'plans' => $plan->name
]);
```

**Retrieve ARR**


```php
ChartMogul\Metrics::arr([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month'
]);
```

**Retrieve Average Revenue Per Account (ARPA)**

```php
ChartMogul\Metrics::arpa([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month'
]);
```

**Retrieve Average Sale Price (ASP)**

```php
ChartMogul\Metrics::asp([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month',
]);
```
**Retrieve Customer Count**

```php
ChartMogul\Metrics::customerCount([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
    'interval' => 'month',
]);
```

**Retrieve Customer Churn Rate**

```php
ChartMogul\Metrics::customerChurnRate([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
]);
```


**Retrieve MRR Churn Rate**

```php
ChartMogul\Metrics::mrrChurnRate([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
]);
```

**Retrieve LTV**

```php
ChartMogul\Metrics::ltv([
    'start-date' => '2015-01-01',
    'end-date' => '2015-11-24',
]);
```

**List Customer Subscriptions**

```php
ChartMogul\Metrics\Customer::subscriptions($cus->uuid);
```

**List Customer Activities**

```php
ChartMogul\Metrics\Activity::all($cus->uuid);
```


### Exceptions

The library throws following Exceptions:

- `ChartMogul\Exceptions\ChartMogulException`
- `ChartMogul\Exceptions\ConfigurationException`
- `ChartMogul\Exceptions\ForbiddenException`
- `ChartMogul\Exceptions\NotFoundException`
- `ChartMogul\Exceptions\ResourceInvalidException`
- `ChartMogul\Exceptions\SchemaInvalidException`

The following table describes the public methods of the error object.

|  Methods  |       Type       |                             Description                             |
|:----------:|:----------------:|:-------------------------------------------------------------------:|
| `getMessage()`  | string           | The error message                                                   |
| `getStatusCode()`     | number           | When the error occurs during an HTTP request, the HTTP status code. |
| `getResponse()` | array or string | HTTP response as array, or raw response if not parsable to array |

## Development


You need `Docker` installed locally to use our `Makefile` workflow.

* Fork it
* Create your feature branch (`git checkout -b my-new-feature`)
* Install dependencies: `make build`
* Fix bugs or add features. Make sure the changes pass the coding guidelines by runing PHP CodeSniffer: `make cs`
* Write tests for your new features. Run tests and check test coverage with `make test`
* If all tests are passed, push to the branch (`git push origin my-new-feature`)
* Create a new Pull Request

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/chartmogul/chartmogul-php.

## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

### The MIT License (MIT)

*Copyright (c) 2016 ChartMogul Ltd.*

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.