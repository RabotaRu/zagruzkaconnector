<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\tests;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\Enums\MessageType;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\Metrics\IStopwatch;
use RabotaRu\ZagruzkaConnector\Metrics\PrometheusMetrics;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessage;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageData;
use RabotaRu\ZagruzkaConnector\Transport\HttpTransportRest;
use RabotaRu\ZagruzkaConnector\ZagruzkaConnector;

class ZagruzkaConnectorTest extends TestCase
{
    public const URL = "https://zagruzka.com";
    /** @var mixed|\PHPUnit\Framework\MockObject\MockObject|\RabotaRu\ZagruzkaConnector\Transport\HttpTransportRest  */
    private $mockTransport;
    /** @var mixed|\PHPUnit\Framework\MockObject\MockObject|\RabotaRu\ZagruzkaConnector\Metrics\PrometheusMetrics  */
    private $mockMetricks;

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
                new MessageType(),
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
        $this->mockMetricks->expects(self::never())->method('addRequestCounter');

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
        $this->mockMetricks->expects(self::once())->method('addRequestCounter');

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
        $this->mockMetricks->expects(self::once())->method('addRequestCounter');

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

    public function testResponseDeliveredParse(): void
    {
        $responseJson = <<<JSON
{
  "id":"8770599",
  "mtNum":"107930572",
  "status":2,
  "type":"SMS",
  "doneDate":"2019-05-05T10:20:35+0300",
  "submitDate":"2019-05-05T10:19:55+0300",
  "sourceAddr":"SOURCE",
  "destAddr": "72101234567",
  "text":"message_text",
  "partCount":"001",
  "errorCode":"0",
  "mccMnc":"25012",
  "trafficType":0
}
JSON;
        $this->mockMetricks->expects(self::once())->method('addResponseCounter');
        $this->mockMetricks->expects(self::never())->method('addDeliveryErrorCounter');

        $zc = new ZagruzkaConnector(self::URL, $this->mockTransport, $this->mockMetricks);

        $response = $zc->processResponseByJson($responseJson);

        $this->assertEquals(2, $response->getStatus());
        $this->assertEquals("0", $response->getErrorCode());
    }

    public function testResponseNotDeliveredParse(): void
    {
        $responseJson = <<<JSON
{
  "id":"8770599",
  "mtNum":"107930572",
  "status":5,
  "type":"SMS",
  "doneDate":"2019-05-05T10:20:35+0300",
  "submitDate":"2019-05-05T10:19:55+0300",
  "sourceAddr":"SOURCE",
  "destAddr": "72101234567",
  "text":"message_text",
  "partCount":"001",
  "errorCode":"1",
  "mccMnc":"25012",
  "trafficType":0
}
JSON;
        $this->mockMetricks->expects(self::once())->method('addResponseCounter');
        $this->mockMetricks->expects(self::once())->method('addDeliveryErrorCounter');

        $zc = new ZagruzkaConnector(self::URL, $this->mockTransport, $this->mockMetricks);

        $response = $zc->processResponseByJson($responseJson);

        $this->assertEquals(5, $response->getStatus());
        $this->assertEquals("1", $response->getErrorCode());
    }
}