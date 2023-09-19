<?php

namespace ChartMogul;

/**
 *
 * @codeCoverageIgnore
 */
class Configuration
{
	const DEFAULT_MAX_RETRIES = 20;

    /**
     * @var null|Configuration
     */
    private static $defaultConfiguration = null;

    /**
     * @var string
     */
    private $apiKey = '';
    /**
     * @var int
     * maximum retry attempts
     */
    private $retries = self::DEFAULT_MAX_RETRIES;

    /**
     * Creates new config object from apiKey
     * @param string $apiKey
     */
    public function __construct($apiKey = '', $retries = self::DEFAULT_MAX_RETRIES)
    {
        $this->apiKey = $apiKey;
        $this->retries = $retries;
    }

    /**
     * Get Api Key
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set Api Key
     * @param string $apiKey
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get retries
     * @return int
     */
    public function getRetries()
    {
        return $this->retries;
    }

    /**
     * Set max retries
     * @param int $retries
     * @return self
     */
    public function setRetries($retries)
    {
        $this->retries = $retries;
        return $this;
    }

    /**
     * Set Default Config object. Default config object is used when no config object is passed during resource call
     * @return  Configuration
     */
    public static function getDefaultConfiguration()
    {
        if (is_null(self::$defaultConfiguration)) {
            self::$defaultConfiguration = new self();
        }
        return self::$defaultConfiguration;
    }

    /**
     * Get the default config object.
     * @param Configuration $config
     * @return  Configuration
     */
    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
        return $config;
    }
}
