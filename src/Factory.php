<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector;

use Prometheus\CollectorRegistry;
use RabotaRu\ZagruzkaConnector\Metrics\PrometheusMetrics;
use RabotaRu\ZagruzkaConnector\Transport\HttpTransportRest;

final class Factory
{
    /**
     * Default configuration for client
     *
     * @param string                        $url
     * @param string                        $prometheusNamespace
     * @param \Prometheus\CollectorRegistry $registry
     *
     * @return ZagruzkaConnector
     */
    public function defaultConnector(
        string $url,
        string $prometheusNamespace,
        CollectorRegistry $registry
    ): ZagruzkaConnector {
        return new ZagruzkaConnector(
            $url,
            new HttpTransportRest(),
            new PrometheusMetrics($prometheusNamespace, $registry)
        );
    }
}
