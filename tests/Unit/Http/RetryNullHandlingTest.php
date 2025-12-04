<?php
namespace ChartMogul\Tests;

use ChartMogul\Http\Retry;
use ChartMogul\Exceptions\NetworkException;

class RetryNullHandlingTest extends TestCase
{
    public function testRetryWithNoRetriesAndNullResult()
    {
        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Request failed with no response');

        $retry = new Retry(0); // No retries

        $callback = function() {
            return null; // Simulate null response
        };

        $retry->retry($callback);
    }

    public function testRetryWithValidResult()
    {
        $retry = new Retry(0); // No retries needed

        $expectedResult = ['data' => 'test'];
        $callback = function() use ($expectedResult) {
            return $expectedResult;
        };

        $result = $retry->retry($callback);

        $this->assertEquals($expectedResult, $result);
    }
}
