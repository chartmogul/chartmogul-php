<?php
namespace ChartMogul\Tests;

use ChartMogul\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultConfiguration()
    {
        $config = Configuration::getDefaultConfiguration();
        $this->assertEquals("", $config->getAccountToken());
        $config->setAccountToken("token");
        $config->setSecretKey("secret");
        $config->setRetries(100);
        $this->assertEquals("token", $config->getAccountToken());
        $this->assertEquals("secret", $config->getSecretKey());
        $this->assertEquals(100, $config->getRetries());
    }
    public function testNewConfiguration()
    {
        $config = new Configuration("token", "secret", 10);
        $this->assertEquals("token", $config->getAccountToken());
        $this->assertEquals("secret", $config->getSecretKey());
        $this->assertEquals(10, $config->getRetries());
    }
}
