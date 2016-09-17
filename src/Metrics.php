<?php

namespace ChartMogul;

use ChartMogul\Metrics;
use ChartMogul\Http\ClientInterface;

/**
 * The Metrics API allows users to programmatically pull the subscription metrics that ChartMogul generates.
 */
class Metrics
{

    /**
     * Retrieves all key metrics, for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\AllKeyMetrics
     */
    public static function all(array $options = [], ClientInterface $client = null)
    {
        return Metrics\AllKeyMetrics::all($options, $client);
    }
    /**
     * Retrieves the Average Revenue Per Account (ARPA), for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\ARPAs
     */
    public static function arpa(array $options = [], ClientInterface $client = null)
    {
        return Metrics\ARPAs::all($options, $client);
    }

    /**
     * Retrieves the Annualized Run Rate (ARR), for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\ARRs
     */
    public static function arr(array $options = [], ClientInterface $client = null)
    {
        return Metrics\ARRs::all($options, $client);
    }

    /**
     * Retrieves the Average Sale Price (ASP), for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\ASPs
     */
    public static function asp(array $options = [], ClientInterface $client = null)
    {
        return Metrics\ASPs::all($options, $client);
    }

    /**
     * Retrieves the Customer Churn Rate, for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\CustomerChurnRates
     */
    public static function customerChurnRate(array $options = [], ClientInterface $client = null)
    {
        return Metrics\CustomerChurnRates::all($options, $client);
    }

    /**
     * Retrieves the number of active customers, for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\CustomerCounts
     */
    public static function customerCount(array $options = [], ClientInterface $client = null)
    {
        return Metrics\CustomerCounts::all($options, $client);
    }

    /**
     * Retrieves the Monthly Recurring Revenue (MRR), for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\MRRs
     */
    public static function mrr(array $options = [], ClientInterface $client = null)
    {
        return Metrics\MRRs::all($options, $client);
    }

    /**
     * Retrieves the Customer Lifetime Value (LTV), for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\LTVs
     */
    public static function ltv(array $options = [], ClientInterface $client = null)
    {
        return Metrics\LTVs::all($options, $client);
    }

    /**
     * Retrieves the Net MRR Churn Rate, for the specified time period.
     * @param array|array $options
     * @param ClientInterface|null $client
     * @return Metrics\MRRChurnRates
     */
    public static function mrrChurnRate(array $options = [], ClientInterface $client = null)
    {
        return Metrics\MRRChurnRates::all($options, $client);
    }
}
