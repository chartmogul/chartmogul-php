<p align="center">
<a href="https://chartmogul.com"><img width="200" src="https://chartmogul.com/assets/img/logo.png"></a>
</p>

<h3 align="center">Official ChartMogul API PHP Client</h3>

<p align="center"><code>chartmogul-php</code> provides convenient PHP bindings for <a href="https://dev.chartmogul.com">ChartMogul's API</a>.</p>
<p align="center">
  <a href="https://packagist.org/packages/chartmogul/chartmogul-php"><img src="https://badge.fury.io/ph/chartmogul%2Fchartmogul-php.svg" alt="PHP Package" /></a>
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

This library requires php 5.5 or above.


**Using Composer**:


```sh
composer require chartmogul/chartmogul-php
```
or in `composer.json`:

```json
{
    "require": {
        "chartmogul/chartmogul-php": "1.0.0"
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


### Test your authentication
```php
print_r(ChartMogul\Ping::ping()->data);
```

## Usage

```php
try {
  $dataSources = ChartMogul\DataSource::all();
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

All array results are wrapped with [`Doctrine\Common\Collections\ArrayCollection`](http://www.doctrine-project.org/api/common/2.3/class-Doctrine.Common.Collections.ArrayCollection.html) for ease of access. See [Exceptoins](#exceptions) for a list of exceptions thrown by this library.

Available methods in Import API:

### [Data Sources](https://dev.chartmogul.com/docs/data-sources)

**Create Datasources**

```php
$ds = ChartMogul\DataSource::create([
  'name' => 'In-house Billing'
]);
```

**Get a Datasource by UUID**

```php
ChartMogul\DataSource::get($uuid);
```

**List Datasources**

```php
ChartMogul\DataSource::all();
```

**Delete a Datasource**

```php
$dataSources = ChartMogul\DataSource::all();
$ds = $dataSources->last();
$ds->destroy();
```

### Customers

**Import a Customer**

```php
ChartMogul\Customer::create([
    "data_source_uuid" => $ds->uuid,
    "external_id" => "cus_0003",
    "name" => "Adam Smith",
    "email" => "adam@smith.com",
    "country" => "US",
    "city" => "New York",
    "lead_created_at" => "2016-10-01T00:00:00.000Z",
    "free_trial_started_at" => "2016-11-02T00:00:00.000Z"
]);
```

**List Customers**

```php
ChartMogul\Customer::all([
  'page' => 1,
  'data_source_uuid' => $ds->uuid
]);
```

**Find Customer By External ID**

```php
ChartMogul\Customer::findByExternalId([
    "data_source_uuid" => "ds_fef05d54-47b4-431b-aed2-eb6b9e545430",
    "external_id" => "cus_0001"
]);
```

**Delete A Customer**

```php
$cus = ChartMogul\Customer::all()->last();
$cus->destroy();
```

**Get a Customer**

```php
ChartMogul\Customer::get($uuid);
```

**Search for Customers**

```php
ChartMogul\Customer::search('adam@smith.com');
```

**Merge Customers**

```php
ChartMogul\Customer::merge([
    'customer_uuid' => $cus1->uuid
], [
    'customer_uuid' => $cus2->uuid
]);

// alternatively:
ChartMogul\Customer::merge([
    'external_id' => $cus1->external_id,
    'data_source_uuid' => $ds->uuid
        ], [
    'external_id' => $cus2->external_id,
    'data_source_uuid' => $ds->uuid
]);
```

**Update a Customer**

```php
$result = ChartMogul\Customer::update([
    'customer_uuid' => $cus1->uuid
        ], [
    'name' => 'New Name'
]);
```


#### Customer Attributes

**Retrieve Customer's Attributes**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$customer->attributes;

```


#### Tags

**Add Tags to a Customer**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$tags = $customer->addTags("important", "Prio1");
```

**Add Tags to Customers with email**

```php
$customers = ChartMogul\Customer::search('adam@smith.com');

foreach ($customers->entries as $customer) {
    $customer->addTags('foo', 'bar', 'baz');
}
```

**Remove Tags from a Customer**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$tags = $customer->removeTags("important", "Prio1");
```

#### Custom Attributes

**Add Custom Attributes to a Customer**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$custom = $customer->addCustomAttributes(
    ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'],
    ['type' => 'Integer', 'key' => 'age', 'value' => 8 ]
);
```


**Add Custom Attributes to Customers with email**

```php
$customers = ChartMogul\Customer::search('adam@smith.com');

foreach ($customers->entries as $customer) {
    $customer->addCustomAttributes(
        ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'],
        ['type' => 'Integer', 'key' => 'age', 'value' => 8 ]
    );
}
```

**Update Custom Attributes of a Customer**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$custom = $customer->updateCustomAttributes(
    ['channel' => 'Twitter'],
    ['age' => 18]
);
```

**Remove Custom Attributes from a Customer**

```php
$customer = ChartMogul\Customer::get($cus->uuid);
$tags = $customer->removeCustomAttributes("age", "channel");
```

### Plans

**Import a Plan**

```php
ChartMogul\Plan::create([
    "data_source_uuid" => $ds->uuid,
    "name" => "Bronze Plan",
    "interval_count" => 1,
    "interval_unit" => "month",
    "external_id" => "plan_0001"
]);
```

**Get a Plan by UUID**

```php
ChartMogul\Plan::get($uuid);
```

**List Plans**

```php
$plans = ChartMogul\Plan::all([
  'page' => 1
]);
```

**Delete A Plan**

```php
$plan = ChartMogul\Plan::all()->last();
$plan->destroy();
```

**Update A Plan**

```php
$plan = ChartMogul\Plan::update(["plan_uuid" => $plan->uuid], [
            "name" => "Bronze Monthly Plan",
            "interval_count" => 1,
            "interval_unit" => "month"
]);
```

### Invoices

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

### Transactions

**Create a Transaction**

```php
ChartMogul\Import\Transactions\Refund::create([
    "invoice_uuid" => $ci->invoices[0]->uuid,
    "date" => "2015-12-25 18:10:00",
    "result" => "successful"
]);
```

The same can be done with Payment class.

### Subscriptions

**List Customer Subscriptions**

```php
$subscriptions = $cus->subscriptions();
```

**Cancel Customer Subscriptions**

```php
$canceldate = '2016-01-01T10:00:00.000Z';
$cus = new ChartMogul\Import\Customer([
    'uuid' => 'cus_uuid'
]);
$subscription = $subscriptions->last()->cancel($canceldate);
```

Or set the cancellation dates:
```php
$cancellationDates = ['2016-01-01T10:00:00.000Z', '2017-01-01T10:00:00.000Z']
$subscription = $subscriptions->last()->setCancellationDates($cancellationDates)
```

### Metrics API


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
ChartMogul\Metrics\Subscriptions::all(
    ["customer_uuid" => $cus->uuid]
);
```

**List Customer Activities**

```php
ChartMogul\Metrics\Activities::all(
    ["customer_uuid" => $cus->uuid]
);
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


## Using Your Own HTTP Client

The library uses `php-http/guzzle6-adapter` as the HTTP client. But you can use any HTTP client that implements [`php-http/client-implementation`](https://packagist.org/providers/php-http/client-implementation) virtual package. For example:


```php
$config = new ChartMogul\Configuration('accountToken','secretKey');

$httpClient = new My\HttpClient();
$client = new ChartMogul\Http\Client($config, $httpClient);

customers = $ChartMogul\Import\Customer::all([
  'page' => 1,
  'data_source_uuid' => $ds->uuid
], $client);

```

Learn more about this virtual package at [here](http://docs.php-http.org/en/latest/httplug/users.html).

## API Documentation

Find the full public API documentation [here](docs/README.md).


## Development


You need `Docker` installed locally to use our `Makefile` workflow.

* Fork it.
* Create your feature branch (`git checkout -b my-new-feature`).
* Install dependencies: `make build`.
* Fix bugs or add features. Make sure the changes pass the coding guidelines by runing PHP CodeSniffer: `make cs`. You can also run `make cbf` to fix some errors automatically.
* Write tests for your new features. Run tests and check test coverage with `make test`.
* Generate API documentation by running `make doc`.
* To run any composer commands or php scripts, use `make composer <commands>` or `make php <script>`.
* If all tests are passed, push to the branch (`git push origin my-new-feature`).
* Create a new Pull Request.

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/chartmogul/chartmogul-php.

## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

### The MIT License (MIT)

*Copyright (c) 2016 ChartMogul Ltd.*

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
