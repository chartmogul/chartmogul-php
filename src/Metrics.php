<?php

namespace ChartMogul;

use ChartMogul\Metrics as NSMetrics;
use ChartMogul\Http\ClientInterface;

/**
 * The Metrics API allows users to programmatically pull the subscription metrics that ChartMogul generates.
 */
class Metrics
{
    /**
     * Retrieves all key metrics, for the specified time period.
     *
     * @param  array|array          $options
     * @param  ClientInterface|null $client
     * @return NSMetrics\AllKeyMetrics
     */
    public static function all(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\AllKeyMetrics::all($options, $client);
    }
    /**
     * Retrieves the Average Revenue Per Account (ARPA), for the specified time period.
     *
     * @inheritDoc
     */
    public static function arpa(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\ARPAs::all($options, $client);
    }

    /**
     * Retrieves the Annualized Run Rate (ARR), for the specified time period.
     *
     * @inheritDoc
     */
    public static function arr(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\ARRs::all($options, $client);
    }

    /**
     * Retrieves the Average Sale Price (ASP), for the specified time period.
     *
     * @inheritDoc
     */
    public static function asp(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\ASPs::all($options, $client);
    }

    /**
     * Retrieves the Customer Churn Rate, for the specified time period.
     *
     * @inheritDoc
     */
    public static function customerChurnRate(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\CustomerChurnRates::all($options, $client);
    }

    /**
     * Retrieves the number of active customers, for the specified time period.
     *
     * @inheritDoc
     */
    public static function customerCount(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\CustomerCounts::all($options, $client);
    }

    /**
     * Retrieves the Monthly Recurring Revenue (MRR), for the specified time period.
     *
     * @inheritDoc
     */
    public static function mrr(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\MRRs::all($options, $client);
    }

    /**
     * Retrieves the Customer Lifetime Value (LTV), for the specified time period.
     *
     * @inheritDoc
     */
    public static function ltv(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\LTVs::all($options, $client);
    }

    /**
     * Retrieves the Net MRR Churn Rate, for the specified time period.
     *
     * @inheritDoc
     */
    public static function mrrChurnRate(array $options = [], ?ClientInterface $client = null)
    {
        return NSMetrics\MRRChurnRates::all($options, $client);
    }
}
