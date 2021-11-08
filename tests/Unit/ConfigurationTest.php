<?php
namespace ChartMogul\Tests;

use ChartMogul\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultConfiguration()
    {
        $config = Configuration::getDefaultConfiguration();
        $this->assertEquals("", $config->getApiKey());
        $config->setApiKey("token");
        $config->setRetries(100);
        $this->assertEquals("token", $config->getApiKey());
        $this->assertEquals(100, $config->getRetries());
    }
    public function testNewConfiguration()
    {
        $config = new Configuration("token", 10);
        $this->assertEquals("token", $config->getApiKey());
        $this->assertEquals(10, $config->getRetries());
    }
}
