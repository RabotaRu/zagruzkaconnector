<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Metrics;

interface IStopwatch
{
    public function getDuration(): float;
}
