<?php


namespace RabotaRu\ZagruzkaConnector;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;

interface IRestConnector
{
    public function sendByRest(
        Request $request,
        ?RestPreSendHook $preSend = null,
        ?RestPostSendHook $postSend = null
    ): ?ResponseInterface;
}
