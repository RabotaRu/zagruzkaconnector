<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\HooksInterfaces;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\Metrics\IStopwatch;

interface RestPostSendHook
{
    public function call(string $url, ResponseInterface $response, ?IStopwatch $stopwatch): void;
}
