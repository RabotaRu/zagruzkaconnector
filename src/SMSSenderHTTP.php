<?php

namespace RabotaRu\ZagruzkaConnector;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\Metrics\IMetric;

class SMSSenderHTTP
{
    private string $url;
    private string $login;
    private string $password;
    private string $sourceName;

    private ?IMetric $metrics;

    public function __construct(
        string   $url,
        string   $login,
        string   $password,
        string   $sourceName,
        ?IMetric $metrics
    )
    {
        $this->url = $url;
        $this->login = $login;
        $this->password = $password;
        $this->sourceName = $sourceName;

        $this->metrics = $metrics;

        $this->httpClient = new Client();
    }

    public function send(
        string  $phone,
        string  $message,
        ?string $sourceName = null
    ): ResponseInterface
    {
        return $this->httpClient->get(
            $this->url . "?" . http_build_query([
                'message' => urlencode($message),
                'clientId' => $phone,
                'serviceId' => $this->login,
                'pass' => $this->password,
                'source' => $sourceName ?? $this->sourceName
            ])
        );
    }
}