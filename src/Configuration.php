<?php


namespace ChartMogul;

/**
* @codeCoverageIgnore
*/
class Configuration
{

    private static $defaultConfiguration = null;


    private $accountToken = '';
    private $secretKey = '';
    private $apiBase = 'https://api.chartmogul.com';

    public function __construct($accountToken = '', $secretKey = '')
    {
        $this->accountToken = $accountToken;
        $this->secretKey = $secretKey;
    }

    public function getAccountToken()
    {
        return $this->accountToken;
    }

    public function setAccountToken($accountToken)
    {
        $this->accountToken = $accountToken;
        return $this;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function getApiBase()
    {
        return $this->apiBase;
    }

    public static function getDefaultConfiguration()
    {
        if (is_null(self::$defaultConfiguration)) {
            self::$defaultConfiguration = new self();
        }
        return self::$defaultConfiguration;
    }

    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
        return $config;
    }
}
