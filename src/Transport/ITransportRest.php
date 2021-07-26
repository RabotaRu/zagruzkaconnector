<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Transport;

use Psr\Http\Message\ResponseInterface;

interface ITransportRest
{
    public function send(string $url, string $body): ResponseInterface;
}
