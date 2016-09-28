<?php


namespace ChartMogul;

/**
 *
 * @codeCoverageIgnore
 */
class Configuration
{

    /**
     * @var null|Configuration
     */
    private static $defaultConfiguration = null;

    /**
     * @var string
     */
    private $accountToken = '';
    /**
     * @var string
     */
    private $secretKey = '';

    /**
     * Creates new config object from accountToken and secretKey
     * @param string $accountToken
     * @param string $secretKey
     */
    public function __construct($accountToken = '', $secretKey = '')
    {
        $this->accountToken = $accountToken;
        $this->secretKey = $secretKey;
    }

    /**
     * Get Account Token
     * @return string
     */
    public function getAccountToken()
    {
        return $this->accountToken;
    }

    /**
     * Set Account Token
     * @param string $accountToken
     * @return self
     */
    public function setAccountToken($accountToken)
    {
        $this->accountToken = $accountToken;
        return $this;
    }

    /**
     * Get Secret Key
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set Secret Key
     * @param string $secretKey
     * @return self
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
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
