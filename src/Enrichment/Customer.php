<?php

namespace ChartMogul\Enrichment;

use ChartMogul\Resource\AbstractResource;

class Customer extends AbstractResource {
    const RESOURCE_PATH = '/v1/customers';

    protected $uuid;

    public $external_id;
    public $name;
    public $email;
    public $company;
    public $country;
    public $state;
    public $city;
    public $zip;
    public $data_source_uuid;

}