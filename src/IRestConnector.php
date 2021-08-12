<?php


namespace RabotaRu\ZagruzkaConnector;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\RestResponse\Response;

interface IRestConnector
{
    /**
     * @param Request                   $request
     * @param RestPreSendHook|null  $preSend
     * @param RestPostSendHook|null $postSend
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function sendByRest(
        Request $request,
        ?RestPreSendHook $preSend = null,
        ?RestPostSendHook $postSend = null
    ): ?ResponseInterface;

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function processResponse(Response $response): Response;

    /**
     * @param array<string, string|int> $response
     *
     * @return Response
     */
    public function processResponseByArray(array $response): Response;

    /**
     * @param string $response
     *
     * @return Response
     */
    public function processResponseByJson(string $response): Response;
}
