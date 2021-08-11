<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class HttpTransportRest implements ITransportRest
{

    /**
     * @var float
     */
    private $timeout = 0.800;
    /**
     * @var float
     */
    private $connectTimeout = 0.100;
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(string $url, string $body): ResponseInterface
    {
        try {
            return $this->httpClient->post(
                $url,
                [
                    RequestOptions::BODY => $body,
                    RequestOptions::HEADERS => [
                        'ContentType' => 'application/json',
                    ],
                    RequestOptions::TIMEOUT => $this->timeout,
                    RequestOptions::CONNECT_TIMEOUT => $this->connectTimeout
                ]
            );
        } catch (ClientException $e) {
            $r = $e->getResponse();
            if (null != $r) {
                return $r;
            }
            throw $e;
        }
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @param float $timeout
     *
     * @return HttpTransportRest
     */
    public function setTimeout(float $timeout): HttpTransportRest
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return float
     */
    public function getConnectTimeout(): float
    {
        return $this->connectTimeout;
    }

    /**
     * @param float $connectTimeout
     *
     * @return HttpTransportRest
     */
    public function setConnectTimeout(float $connectTimeout): HttpTransportRest
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }
}
