<?php

use ChartMogul\Http\Client;
use ChartMogul\Metrics;
use ChartMogul\Summary;
use ChartMogul\Metrics\AllKeyMetric;
use ChartMogul\Metrics\LTV;
use ChartMogul\Exceptions\ChartMogulException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;

class MetricsTest extends \PHPUnit\Framework\TestCase
{

    const ALL_JSON = '{
        "entries":[
            {
              "date":"2015-01-31",
              "customer-churn-rate":20,
              "mrr-churn-rate":14,
              "ltv":1250.3,
              "customers":331,
              "asp":125,
              "arpa":1250,
              "arr":254000,
              "mrr":21166
            },
            {
              "date":"2015-02-28",
              "customer-churn-rate":20,
              "mrr-churn-rate":22,
              "ltv":1248,
              "customers":329,
              "asp":125,
              "arpa":1250,
              "arr":238000,
              "mrr":21089
            }
        ]
    }';

    const LTV_JSON = '{
      "entries":[
        {
          "date":"2015-01-31",
          "ltv":0
        },
        {
          "date":"2015-02-28",
          "ltv":0
        },
        {
          "date":"2015-03-31",
          "ltv":1862989.7959183701
        }
      ],
      "summary":{
        "current":980568,
        "previous":980568,
        "percentage-change":0.0
      }
    }';

    public function testAll()
    {
        $stream = Psr7\stream_for(MetricsTest::ALL_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = Metrics::all(["interval" => "month"],$cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("interval=month", $uri->getQuery());
        $this->assertEquals("/v1/metrics/all", $uri->getPath());

        $this->assertTrue($result->entries[0] instanceof AllKeyMetric);
        $this->assertEquals($result->entries[0]->date, "2015-01-31");

    }
    public function testLtv(){

        $stream = Psr7\stream_for(MetricsTest::LTV_JSON);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $mockClient = new \Http\Mock\Client();
        $mockClient->addResponse($response);

        $cmClient = new Client(null, $mockClient);
        $result = Metrics::ltv([
            "start-date" => "2015-01-01",
            "end-date" => "2015-11-01",
        ],$cmClient);
        $request = $mockClient->getRequests()[0];

        $this->assertEquals("GET", $request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals("start-date=2015-01-01&end-date=2015-11-01", $uri->getQuery());
        $this->assertEquals("/v1/metrics/ltv", $uri->getPath());

        $this->assertTrue($result->entries[0] instanceof LTV);
        $this->assertTrue($result->summary instanceof Summary);
    }
}
