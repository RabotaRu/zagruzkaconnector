<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Metrics;

interface IMetric
{
    public function startTimer(): IStopwatch;
    public function observeDuration(IStopwatch $stopWatch, int $code, string $name): void;
    public function addResponseCounter(int $code, string $name, int $val = 1): void;
}
