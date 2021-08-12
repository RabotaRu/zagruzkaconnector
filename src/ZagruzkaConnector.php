<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\Metrics\IMetric;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\RestResponse\Response;
use RabotaRu\ZagruzkaConnector\Transport\ITransportRest;

class ZagruzkaConnector implements IRestConnector
{
    public const METRIC_REQUEST_DURATION_NAME = 'rest_request_duration';
    public const METRIC_REQUEST_COUNT_NAME_PREFIX = 'rest_request_count_';
    public const METRIC_RESPONSE_COUNT_NAME_PREFIX = 'rest_response_count_';
    public const METRIC_DELIVERY_ERROR_COUNT_NAME_PREFIX = 'delivery_error_count_';

    /**
     * @var ITransportRest
     */
    private $transport;
    /**
     * @var string
     */
    private $url;
    /**
     * @var IMetric|null
     */
    private $metrics;

    public function __construct(string $url, ITransportRest $transport, ?IMetric $metrics = null)
    {
        $this->transport = $transport;
        $this->url = $url;
        $this->metrics = $metrics;
    }

    /**
     * @param Request               $request
     * @param RestPreSendHook|null  $preSend
     * @param RestPostSendHook|null $postSend
     *
     * @return ResponseInterface|null
     */
    public function sendByRest(
        Request $request,
        ?RestPreSendHook $preSend = null,
        ?RestPostSendHook $postSend = null
    ): ?ResponseInterface {
        $stopwatch = null;
        if (null !== $preSend && !$preSend->call($this->url, $request)) {
            return null;
        }
        
        if (null !== $this->metrics) {
            $stopwatch = $this->metrics->startTimer();
        }
        
        $response = $this->transport->send($this->url, \GuzzleHttp\json_encode($request));
        if (null !== $this->metrics) {
            /** @phpstan-ignore-next-line */
            $this->metrics->observeDuration($stopwatch, $response->getStatusCode(), self::METRIC_REQUEST_DURATION_NAME);
            $this->metrics->addRequestCounter(
                $response->getStatusCode(),
                self::METRIC_REQUEST_COUNT_NAME_PREFIX . $request->getMessage()->getType()->getType()
            );
        }
        if (null !== $postSend) {
            $postSend->call($this->url, $response, $stopwatch);
        }
        return $response;
    }

    public function processResponse(Response $response): Response
    {
        if ($this->metrics !== null) {
            $this->metrics->addResponseCounter(
                $response->getStatus(),
                self::METRIC_RESPONSE_COUNT_NAME_PREFIX . $response->getType()->getType()
            );
            if ($response->isNotDelivery()) {
                $this->metrics->addDeliveryErrorCounter(
                    $response->getErrorCode(),
                    self::METRIC_DELIVERY_ERROR_COUNT_NAME_PREFIX . $response->getType()->getType()
                );
            }
        }
        return $response;
    }

    /**
     * @throws \Exception
     */
    public function processResponseByArray(array $response): Response
    {
        return $this->processResponse(new Response($response));
    }

    /**
     * @param string $response
     *
     * @return \RabotaRu\ZagruzkaConnector\RestResponse\Response
     * @throws \Exception
     */
    public function processResponseByJson(string $response): Response
    {
        return $this->processResponseByArray(\GuzzleHttp\json_decode($response, true));
    }
}
