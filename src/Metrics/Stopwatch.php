<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Metrics;

class Stopwatch implements IStopwatch
{
    /**
     * @var float
     */
    private $startTime;

    /**
     * StopWatch constructor.
     */
    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return microtime(true) - $this->startTime;
    }
}
