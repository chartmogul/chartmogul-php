<p align="center">
<a href="https://chartmogul.com"><img width="200" src="https://user-images.githubusercontent.com/5329361/42206299-021e4184-7ea7-11e8-8160-8ecd5d9948b8.png"></a>
</p>

<h3 align="center">Official ChartMogul API PHP Client</h3>

<p align="center"><code>chartmogul-php</code> provides convenient PHP bindings for <a href="https://dev.chartmogul.com">ChartMogul's API</a>.</p>
<p align="center">
  <a href="https://packagist.org/packages/chartmogul/chartmogul-php" target="_blank"><img class="badge" src="http://poser.pugx.org/chartmogul/chartmogul-php/v"></a>
  <a href="#"><img src="https://github.com/chartmogul/chartmogul-php/actions/workflows/test.yml/badge.svg" alt="Build Status"/></a>
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

For older php versions (`< 7.4`) use `1.x.x` releases of this library.

For php version `>=7.4` use the latest releases (`5.x.x`) of the library


The library doesn't depend on any concrete HTTP client libraries. Follow the instructions [here](http://docs.php-http.org/en/latest/httplug/users.html) to find out how to include a HTTP client.

Here's an example with `Guzzle HTTP client`:

```sh
composer require chartmogul/chartmogul-php:^5.0 php-http/guzzle6-adapter:^2.0.1 http-interop/http-factory-guzzle:^1.0
```

## Configuration
Setup the default configuration with your ChartMogul api key:

```php
require('./vendor/autoload.php');

ChartMogul\Configuration::getDefaultConfiguration()
    ->setApiKey('<YOUR_API_KEY>');
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

All array results are wrapped with [`Doctrine\Common\Collections\ArrayCollection`](http://www.doctrine-project.org/api/common/2.3/class-Doctrine.Common.Collections.ArrayCollection.html) for ease of access. See [Exceptions](#exceptions) for a list of exceptions thrown by this library.

Available methods in Import API:

### [Data Sources](https://dev.chartmogul.com/reference/sources/)

**Create Datasources**

```php
$ds = ChartMogul\DataSource::create([
  'name' => 'In-house Billing'
]);
```

**Get a Datasource**

```php
ChartMogul\DataSource::retrieve($uuid);
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
ChartMogul\Customer::retrieve($uuid);
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

**Unmerge Customers**

```php
ChartMogul\Customer::unmerge(
    $cus->uuid,
    $cus->external_id,
    $ds->uuid,
    ["tasks", "opportunities", "notes"]
);
```

**Update a Customer**

```php
$result = ChartMogul\Customer::update([
    'customer_uuid' => $cus1->uuid
        ], [
    'name' => 'New Name'
]);
```

**Connect Subscriptions**

```php
ChartMogul\Customer::connectSubscriptions("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", [
    "subscriptions" => [
        [
            "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
            "external_id" => "d1c0c885-add0-48db-8fa9-0bdf5017d6b0",
        ],
        [
            "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
            "external_id" => "9db5f4a1-1695-44c0-8bd4-de7ce4d0f1d4",
        ],
    ]
]);
```

OR

```php
$subscription1->connect("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", $subscriptions);

```

**Disconnect Subscriptions**

```php
ChartMogul\Customer::disconnectSubscriptions("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", [
    "subscriptions" => [
        [
            "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
            "external_id" => "d1c0c885-add0-48db-8fa9-0bdf5017d6b0",
        ],
        [
            "data_source_uuid" => "ds_ade45e52-47a4-231a-1ed2-eb6b9e541213",
            "external_id" => "9db5f4a1-1695-44c0-8bd4-de7ce4d0f1d4",
        ],
    ]
]);
```

OR

```php
$subscription1->disconnect("cus_5915ee5a-babd-406b-b8ce-d207133fb4cb", $subscriptions);

```

#### Customer Attributes

**Retrieve Customer's Attributes**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$customer->attributes;

```


#### Tags

**Add Tags to a Customer**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$tags = $customer->addTags("important", "Prio1");
```

**Add Tags Using Array (Alternative)**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$tagsArray = ["important", "Prio1", "enterprise"];
$tags = $customer->addTags($tagsArray);
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
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$tags = $customer->removeTags("important", "Prio1");
```

**Remove Tags Using Array (Alternative)**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$tagsToRemove = ["important", "Prio1"];
$tags = $customer->removeTags($tagsToRemove);
```

#### Custom Attributes

**Add Custom Attributes to a Customer**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$custom = $customer->addCustomAttributes(
    ['type' => 'String', 'key' => 'channel', 'value' => 'Facebook'],
    ['type' => 'Integer', 'key' => 'age', 'value' => 8 ]
);
```

**Add Custom Attributes Using Array (Alternative)**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);

// Build attributes array dynamically
$attributes = [];
if (!empty($channel)) {
    $attributes[] = ['type' => 'String', 'key' => 'channel', 'value' => $channel];
}
if (!empty($age)) {
    $attributes[] = ['type' => 'Integer', 'key' => 'age', 'value' => $age];
}

$custom = $customer->addCustomAttributes($attributes);
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
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$custom = $customer->updateCustomAttributes(
    ['type' => 'String', 'key' => 'channel', 'value' => 'Twitter'],
    ['type' => 'Integer', 'key' => 'age', 'value' => 18]
);
```

**Update Custom Attributes Using Array (Alternative)**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$attributeUpdates = [
    ['type' => 'String', 'key' => 'channel', 'value' => 'Twitter'],
    ['type' => 'Integer', 'key' => 'age', 'value' => 18]
];
$custom = $customer->updateCustomAttributes($attributeUpdates);
```

**Remove Custom Attributes from a Customer**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$tags = $customer->removeCustomAttributes("age", "channel");
```

**Remove Custom Attributes Using Array (Alternative)**

```php
$customer = ChartMogul\Customer::retrieve($cus->uuid);
$attributesToRemove = [
    ['key' => 'age'],
    ['key' => 'channel']
];
$tags = $customer->removeCustomAttributes($attributesToRemove);
```

**List Contacts from a customer**

```php
$customer = ChartMogul\Customer::retrieve($uuid);
$contacts = $customer->contacts([
  'cursor' => 'aabbccdd...'
]);
```

**Create a Contact from a customer**

```php
$customer = ChartMogul\Customer::retrieve($uuid);
$new_customer = $customer->createContact([
  "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
  "first_name" => "Adam",
  "last_name" => "Smith",
]);
```

**List Customer Notes from a customer**
```php
$customer = ChartMogul\Customer::retrieve($uuid);
$customer_notes = $customer->notes([
  'cursor' => 'aabbccdd...'
]);
```

**Create a Customer Note from a customer**
```php
$customer = ChartMogul\Customer::retrieve($uuid);
$new_customer_note = $customer->createNote([
  'type' => 'note',
  'text' => 'This is a note'
]);
```

**List Opportunities from a customer**

```php
$customer = ChartMogul\Customer::retrieve($uuid);
$opportunities = $customer->opportunities([
  'cursor' => 'aabbccdd...'
]);
```

**Create an Opportunity from a customer**

```php
$customer = ChartMogul\Customer::retrieve($uuid);
$new_opportunity = $customer->createOpportunity([
  'owner' => 'owner@example.com',
  'pipeline' => 'Sales',
  'pipeline_stage' => 'Qualified',
  'estimated_close_date' => '2022-03-30',
  'currency' => 'USD',
  'amount_in_cents' => 10000,
  'type' => 'one-time',
  'forecast_category' => 'Best Case',
  'win_likelihood' => 80,
  'custom' => [{key: 'custom_key', value: 'custom_value'}]
]);
```

**List Tasks for a customer**
```php
$customer = ChartMogul\Customer::retrieve($customer_uuid);
$tasks = $customer->tasks([
  'cursor' => 'aabbccdd...'
]);
```

**Create a Task for a customer**
```php
$customer = ChartMogul\Customer::retrieve($customer_uuid);
$new_task = $customer->createTask([
  'assignee' => 'customer@example.com',
  'task_details' => 'This is some task details text.',
  'due_date' => '2025-04-30T00:00:00Z',
  'completed_at' => '2025-04-20T00:00:00Z',
]);
```

### Customer Notes

**List Customer Notes**
```php
$customer_notes = ChartMogul\CustomerNote::all([
    'customer_uuid' => $uuid,
    'cursor' => 'aabbccdd...'
])
```

**Create a Customer Note**
```php
$customer_note = ChartMogul\CustomerNote::create([
    'customer_uuid': $uuid,
    'type' => 'note',
    'text' => 'This is a note'
])
```

**Get a Customer Note**
```php
$customer_note = ChartMogul\CustomerNote::retrieve($note_uuid)
```

**Update a Customer Note**
```php
$updated_customer_note = ChartMogul\CustomerNote::update($note_uuid, [
  'text' => 'This is a new note'
]);
```

**Delete a Customer Note**
```php
$customer_note = ChartMogul\CustomerNote::retrieve($note_uuid)
$customer_note->destroy();
```

### Contacts

**List Contacts**

```php
$contacts = ChartMogul\Contacts::all([
  'cursor' => 'aabbccdd...'
]);
```

**Create a Contact**

```php
$new_contact = ChartMogul\Contact::create([
  "customer_uuid" => "cus_00000000-0000-0000-0000-000000000000",
  "data_source_uuid" => "ds_00000000-0000-0000-0000-000000000000",
  "first_name" => "Adam",
  "last_name" => "Smith",
]);
```

**Get a Contact**

```php
$contact = ChartMogul\Contact::retrieve($uuid);
```

**Delete A Contact**

```php
$contact = ChartMogul\Contact::retrieve($uuid);
$contact->destroy();
```

**Update a Contact**

```php
$updated_contact = ChartMogul\Contact::update([
    'contact_uuid' => $uuid
        ], [
    'first_name' => 'New Name'
]);
```

**Merge Contacts**

```php
$merged_contact = ChartMogul\Contact::merge($into_contact_uuid, $from_contact_uuid);
```

### Opportunities

**List Opportunities**

```php
$opportunities = ChartMogul\Opportunity::all([
    'cursor' => 'aabbccdd...'
])
```

**Create an Opportunity**

```php
$opportunity = ChartMogul\Opportunity::create([
    'customer_uuid' => $uuid,
    'owner' => 'test1@example.org',
    'pipeline' => 'New business 1',
    'pipeline_stage' => 'Discovery',
    'estimated_close_date' => '2023-12-22',
    'currency' => 'USD',
    'amount_in_cents' => 100,
    'type' => 'recurring',
    'forecast_category' => 'pipeline',
    'win_likelihood' => 3,
    'custom' => [{ 'key': 'from_campaign', 'value': true }]
])
```

**Get an Opportunity**

```php
$opportunity = ChartMogul\Opportunity::retrieve($opportunity_uuid)
```

**Update an Opportunity**

```php
$updated_opportunity = ChartMogul\Opportunity::update($opportunity_uuid, [
  'estimated_close_date' => '2024-12-22',
]);
```

**Delete an Opportunity**

```php
$opportunity = ChartMogul\Opportunity::retrieve($opportunity_uuid)
$opportunity->destroy();
```

### Tasks

**List Tasks**
```php
$tasks = ChartMogul\Task::all([
    'customer_uuid' => $customer_uuid,
    'cursor' => 'aabbccdd...'
])
```

**Create a Task**
```php
$task = ChartMogul\Task::create([
    'customer_uuid' => $customer_uuid,
    'task_details' => 'This is some task details text.',
    'assignee' => 'customer@example.com',
    'due_date' => '2025-04-30T00:00:00Z',
    'completed_at' => '2025-04-20T00:00:00Z',
])
```

**Get a Task**
```php
$task = ChartMogul\Task::retrieve($task_uuid)
```

**Update a Task**
```php
$updated_task = ChartMogul\Task::update($task_uuid, [
  'task_details' => 'This is some other task details text.'
]);
```

**Delete a Task**
```php
$task = ChartMogul\Task::retrieve($task_uuid)
$task->destroy();
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
ChartMogul\Plan::retrieve($uuid);
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

### Subscription Events

**List Subscriptions Events**

```php
$subscription_events = ChartMogul\SubscriptionEvent::all();
```

**Create Subscription Event**

```php
ChartMogul\SubscriptionEvent::create(['subscription_event' => [
    "external_id" => "evnt_026",
    "customer_external_id" => "cus_0003",
    "data_source_uuid" => $ds->uuid,
    "event_type" => "subscription_start_scheduled",
    "event_date" => "2022-03-30",
    "effective_date" => "2022-04-01",
    "subscription_external_id" => "sub_0001",
    "plan_external_id" => "plan_0001",
    "currency" => "USD",
    "amount_in_cents" => 1000
    "quantity" => 1
]]);
```

**Delete Subscription Event**

```php
ChartMogul\SubscriptionEvent::destroyWithParams(['subscription_event' => [
    "id" => "some_event_id"
]]);
```

**Update Subscription Event**

```php
ChartMogul\SubscriptionEvent::updateWithParams(['subscription_event' => [
    "id" => "some_event_id",
    "currency" => "EUR",
    "amount_in_cents" => "100"
]]);
```


### Plan Groups

**Create a Plan Group**

```php
ChartMogul\PlanGroup::create([
    "name" => "Bronze Plan",
    "plans" => [$plan_uuid_1, $plan_uuid_2],
]);
```

**Get a Plan Group by UUID**

```php
ChartMogul\PlanGroup::retrieve($uuid);
```

**List Plan Groups**

```php
$plan_groups = ChartMogul\PlanGroup::all([
  'page' => 1
]);
```

**Delete A Plan Group**

```php
$plan_group = ChartMogul\PlanGroup::all()->last();
$plan_group->destroy();
```

**Update A Plan Group**

```php
$plan_group = ChartMogul\PlanGroup::update(
  ["plan_group_uuid" => $plan_group->uuid],
  [
  "name" => "A new plan group name",
  "plans" => [$plan_uuid_1, $plan_uuid_2]
]);
```

**List Plans In A Plan Group**

```php
$plan_group_plans = ChartMogul\PlanGroups\Plan::all(
  ["plan_group_uuid" => $plan_group->uuid]
);
```

### Invoices

**Import Invoices**

```php
$plan = ChartMogul\Plan::all()->first();
$cus = ChartMogul\Customer::all()->first();

$line_item_1 = new ChartMogul\LineItems\Subscription([
    'subscription_external_id' => "sub_0001",
    'subscription_set_external_id' => 'set_0001',
    'plan_uuid' =>  $plan->uuid,
    'service_period_start' =>  "2015-11-01 00:00:00",
    'service_period_end' =>  "2015-12-01 00:00:00",
    'amount_in_cents' => 5000,
    'quantity' => 1,
    'discount_code' => "PSO86",
    'discount_amount_in_cents' => 1000,
    'tax_amount_in_cents' => 900,
    'transaction_fees_currency' => "EUR",
    'discount_description' => "5 EUR"
]);

$line_item_2 = new ChartMogul\LineItems\OneTime([
    "description" => "Setup Fees",
    "amount_in_cents" => 2500,
    "quantity" => 1,
    "discount_code" => "PSO86",
    "discount_amount_in_cents" => 500,
    "tax_amount_in_cents" => 450,
    "transaction_fees_currency" => "EUR",
    "discount_description" => "2 EUR"
]);

$transaction = new ChartMogul\Transactions\Payment([
    "date" => "2015-11-05T00:14:23.000Z",
    "result" => "successful"
]);

$invoice = new ChartMogul\Invoice([
    'external_id' => 'INV0001',
    'date' =>  "2015-11-01 00:00:00",
    'currency' => 'USD',
    'due_date' => "2015-11-15 00:00:00",
    'line_items' => [$line_item_1, $line_item_2],
    'transactions' => [$transaction]
]);

$ci = ChartMogul\CustomerInvoices::create([
    'customer_uuid' => $cus->uuid,
    'invoices' => [$invoice]
]);

```

**List Customer Invoices**

```php
$ci = ChartMogul\CustomerInvoices::all([
    'customer_uuid' => $cus->uuid,
    'page' => 1,
    'per_page' => 200
]);
```

**List Invoices**

```php
$invoices = ChartMogul\Invoice::all([
    'external_id' => 'my_invoice',
    'page' => 1,
    'per_page' => 200
]);
```

**Retrieve an Invoice**

```php
$invoice = ChartMogul\Invoice::retrieve('inv_uuid');
```

### Transactions

**Create a Transaction**

```php
ChartMogul\Transactions\Refund::create([
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
$subscription = new ChartMogul\Subscription(["uuid" => $subsUUID])
// or use some existing instance of subsctiption, eg. fetched from Customer->subscriptions
$canceldate = '2016-01-01T10:00:00.000Z';
$subscription->cancel($canceldate);
```

Or set the cancellation dates:
```php
$subscription = new ChartMogul\Subscription(["uuid" => $subsUUID])
$cancellationDates = ['2016-01-01T10:00:00.000Z', '2017-01-01T10:00:00.000Z']
$subscription->setCancellationDates($cancellationDates)
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
ChartMogul\Metrics\Customers\Subscriptions::all([
    "customer_uuid" => $cus->uuid
]);
```

**List Customer Activities**

```php
ChartMogul\Metrics\Customers\Activities::all([
    "customer_uuid" => $cus->uuid
]);
```

**List Activities**

```php
ChartMogul\Metrics\Activities::all([
    'start-date' => '2020-06-02T00:00:00Z',
    'per-page' => 100
]);

**Create an Activities Export**

```php
ChartMogul\Metrics\ActivitiesExport::create([
    'start-date' => '2020-06-02T00:00:00Z',
    'end-date' =>  '2021-06-02T00:00:00Z'
    'type' => 'churn'
]);
```

**Retrieve an Activities Export**

```php
$id = '7f554dba-4a41-4cb2-9790-2045e4c3a5b1';
ChartMogul\Metrics\ActivitiesExport::retrieve($id);
```

### Account

**Retrieve Account Details**

```php
ChartMogul\Account::retrieve();
```

### Exceptions

The library throws following Exceptions:

- `ChartMogul\Exceptions\ChartMogulException`
- `ChartMogul\Exceptions\ConfigurationException`
- `ChartMogul\Exceptions\ForbiddenException`
- `ChartMogul\Exceptions\NetworkException`
- `ChartMogul\Exceptions\NotFoundException`
- `ChartMogul\Exceptions\ResourceInvalidException`
- `ChartMogul\Exceptions\SchemaInvalidException`

The following table describes the public methods of the error object.

|  Methods  |       Type       |                             Description                             |
|:----------:|:----------------:|:-------------------------------------------------------------------:|
| `getMessage()`  | string           | The error message                                                   |
| `getStatusCode()`     | number           | When the error occurs during an HTTP request, the HTTP status code. |
| `getResponse()` | array or string | HTTP response as array, or raw response if not parsable to array |


### Rate Limits & Exponential Backoff

The library will keep retrying if the request exceeds the rate limit or if there's any network related error.
By default, the request will be retried for 20 times (approximated 15 minutes) before finally giving up.

You can change the retry count using `Configuration` object:

```php
ChartMogul\Configuration::getDefaultConfiguration()
    ->setApiKey('<YOUR_API_KEY>')
    ->setRetries(15); //0 disables retrying
```


## Development


You need `Docker` installed locally to use our `Makefile` workflow.

* Fork it.
* Create your feature branch (`git checkout -b my-new-feature`).
* Install dependencies: `make build`.
* Install Composer vendor dependencies with `make composer install`
* Write tests for your new features. Run tests and check test coverage with `make test`.
* To run any composer commands or php scripts, use `make composer <commands>` or `make php <script>`.
* If all tests are passed, push to the branch (`git push origin my-new-feature`).
* Create a new Pull Request.

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/chartmogul/chartmogul-php.

## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

### The MIT License (MIT)

*Copyright (c) 2019 ChartMogul Ltd.*

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
