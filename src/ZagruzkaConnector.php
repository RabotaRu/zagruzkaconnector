<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\Metrics\IMetric;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\Transport\ITransportRest;

class ZagruzkaConnector implements IRestConnector
{
    public const METRIC_REQUEST_DURATION_NAME = 'rest_request_duration';
    public const METRIC_REQUEST_COUNT_NAME_PREFIX = 'rest_count_';
    
    /**
     * @var ITransportRest
     */
    private ITransportRest $transport;
    /**
     * @var string
     */
    private string $url;
    /**
     * @var IMetric|null
     */
    private ?IMetric $metrics;

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
     * @throws \JsonException
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
        
        $response = $this->transport->send($this->url, (string)json_encode($request, JSON_THROW_ON_ERROR));
        if (null !== $this->metrics) {
            /** @phpstan-ignore-next-line */
            $this->metrics->observeDuration($stopwatch, $response->getStatusCode(), self::METRIC_REQUEST_DURATION_NAME);
            $this->metrics->addResponseCounter(
                $response->getStatusCode(),
                self::METRIC_REQUEST_COUNT_NAME_PREFIX . $request->getMessage()->getType()->getType(),
            );
        }
        if (null !== $postSend) {
            $postSend->call($this->url, $response, $stopwatch);
        }
        return $response;
    }
}
