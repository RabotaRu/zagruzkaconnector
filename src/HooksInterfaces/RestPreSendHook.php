<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\HooksInterfaces;

use RabotaRu\ZagruzkaConnector\RestRequest\Request;

interface RestPreSendHook
{
    public function call(string $url, Request $request): bool;
}
