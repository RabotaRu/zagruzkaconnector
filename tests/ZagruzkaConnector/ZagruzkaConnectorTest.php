<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\tests;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\Metrics\IMetric;
use RabotaRu\ZagruzkaConnector\Metrics\IStopwatch;
use RabotaRu\ZagruzkaConnector\Metrics\PrometheusMetrics;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessage;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageData;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageType;
use RabotaRu\ZagruzkaConnector\Transport\HttpTransportRest;
use RabotaRu\ZagruzkaConnector\Transport\ITransportRest;
use RabotaRu\ZagruzkaConnector\ZagruzkaConnector;

class ZagruzkaConnectorTest extends TestCase
{
    public const URL = "https://zagruzka.com";
    private ITransportRest $mockTransport;
    private IMetric $mockMetricks;

    public function setUp(): void
    {
        $this->mockTransport = $this->createMock(HttpTransportRest::class);
        $this->mockMetricks = $this->createMock(PrometheusMetrics::class);
    }

    protected function generateRequest(): Request
    {
        return new Request(
            "qwerty",
            "login",
            "password",
            "+79261234567",
            new RequestMessage(
                new RequestMessageType(),
                new RequestMessageData(
                    "test",
                    "fromTest"
                )
            )
        );
    }

    public function testHookPreSendFalse(): void
    {
        $this->mockTransport->expects(self::never())->method('send');
        $this->mockMetricks->expects(self::never())->method('startTimer');
        $this->mockMetricks->expects(self::never())->method('observeDuration');
        $this->mockMetricks->expects(self::never())->method('addResponseCounter');

        $zc = new ZagruzkaConnector(self::URL, $this->mockTransport, $this->mockMetricks);
        $zc->sendByRest(
            $this->generateRequest(),
            new class implements RestPreSendHook {
                public function call(string $url, Request $request): bool
                {
                    return false;
                }
            }
        );
    }

    public function testHookPreSendTrue(): void
    {
        $this->mockTransport->expects(self::once())->method('send')->willReturn(new Response());
        $this->mockMetricks->expects(self::once())->method('startTimer');
        $this->mockMetricks->expects(self::once())->method('observeDuration');
        $this->mockMetricks->expects(self::once())->method('addResponseCounter');

        $zc = new ZagruzkaConnector(self::URL, $this->mockTransport, $this->mockMetricks);
        /** @var ResponseInterface $response */
        $response = $zc->sendByRest(
            $this->generateRequest(),
            new class implements RestPreSendHook {
                public function call(string $url, Request $request): bool
                {
                    TestCase::assertEquals(ZagruzkaConnectorTest::URL, $url);
                    TestCase::assertEquals("test", $request->getMessage()->getData()->getText());
                    return true;
                }
            }
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testHookPostSend(): void
    {
        $this->mockTransport->expects(self::once())->method('send')->willReturn(new Response());
        $this->mockMetricks->expects(self::once())->method('startTimer');
        $this->mockMetricks->expects(self::once())->method('observeDuration');
        $this->mockMetricks->expects(self::once())->method('addResponseCounter');

        $zc = new ZagruzkaConnector(self::URL, $this->mockTransport, $this->mockMetricks);
        /** @var ResponseInterface $response */
        $response = $zc->sendByRest(
            $this->generateRequest(),
            null,
            new class implements RestPostSendHook {
                public function call(string $url, ResponseInterface $response, ?IStopwatch $stopwatch): void
                {
                    TestCase::assertEquals(ZagruzkaConnectorTest::URL, $url);
                    TestCase::assertEquals(200, $response->getStatusCode());
                }
            }
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}