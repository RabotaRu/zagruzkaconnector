<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Metrics;

use Prometheus\CollectorRegistry;
use Prometheus\Exception\MetricsRegistrationException;

class PrometheusMetrics implements IMetric
{

    /**
     * @var \Prometheus\CollectorRegistry
     */
    private $registry;
    /**
     * @var string
     */
    private $namespace;

    public function __construct(
        string $namespace,
        CollectorRegistry $registry
    ) {
        $this->registry = $registry;
        $this->namespace = $namespace;
    }

    /**
     * @return IStopwatch
     */
    public function startTimer(): IStopwatch
    {
        return new Stopwatch();
    }

    /**
     * @param IStopwatch $stopWatch
     * @param int        $code
     * @param string     $name
     *
     * @throws MetricsRegistrationException
     */
    public function observeDuration(IStopwatch $stopWatch, int $code, string $name = 'request_duration'): void
    {
        $duration = $stopWatch->getDuration();
        $this->registry
            ->getOrRegisterHistogram(
                $this->namespace,
                $name,
                'ZagruzkaConnector.com request duration with http status code labels',
                ['code']
            )
            ->observe(
                $duration,
                [(string)$code]
            );
    }

    /**
     * @param int    $code
     * @param string $name
     * @param int    $val
     *
     * @throws MetricsRegistrationException
     */
    public function addRequestCounter(int $code, string $name = 'request_count', int $val = 1): void
    {
        $this->registry->getOrRegisterCounter(
            $this->namespace,
            $name,
            'ZagruzkaConnector.com request counter with http status code labels',
            ['code']
        )
            ->incBy(
                $val,
                [(string)$code]
            );
    }

    /**
     * @param int    $code
     * @param string $name
     * @param int    $val
     *
     * @throws MetricsRegistrationException
     */
    public function addResponseCounter(int $code, string $name = 'response_count', int $val = 1): void
    {
        $this->registry->getOrRegisterCounter(
            $this->namespace,
            $name,
            'ZagruzkaConnector.com response counter with status code labels',
            ['code']
        )
            ->incBy(
                $val,
                [(string)$code]
            );
    }

    /**
     * @param string $code
     * @param string $name
     * @param int    $val
     *
     * @throws MetricsRegistrationException
     */
    public function addDeliveryErrorCounter(string $code, string $name = 'delivery_error_count', int $val = 1): void
    {
        $this->registry->getOrRegisterCounter(
            $this->namespace,
            $name,
            'ZagruzkaConnector.com delivery error counter with error code code labels',
            ['code']
        )
            ->incBy(
                $val,
                [$code]
            );
    }
}
