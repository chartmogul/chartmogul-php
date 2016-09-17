<?php

namespace ChartMogul\Metrics;

class MRRs extends AbstractMetric
{
    const ENTRY_CLASS = MRR::class;
    const RESOURCE_PATH = '/v1/metrics/mrr';
    const RESOURCE_NAME = 'MRRs';
}
