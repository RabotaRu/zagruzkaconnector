<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector;

use Psr\Http\Message\ResponseInterface;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook;
use RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook;
use RabotaRu\ZagruzkaConnector\RestRequest\Request;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessage;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageData;
use RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageType;
use Ramsey\Uuid\Uuid;

class SMSSender
{
    /**
     * @var IRestConnector
     */
    private $connector;
    /** @var string  */
    private $login;
    /** @var string  */
    private $password;
    /** @var string  */
    private $serviceName;

    /** @var \RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPreSendHook|null  */
    private $preSendHook = null;
    /** @var \RabotaRu\ZagruzkaConnector\HooksInterfaces\RestPostSendHook|null  */
    private $postSendHook = null;

    public function __construct(
        IRestConnector $connector,
        string $login,
        string $password,
        string $serviceName
    ) {
        $this->connector = $connector;
        $this->login = $login;
        $this->password = $password;
        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    protected function getUUID(): string
    {
        return Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON)->toString();
    }

    /**
     * @param string $id
     * @param string $destAddr
     * @param string $text
     *
     * @return Request
     */
    protected function getRequest(string $id, string $destAddr, string $text): Request
    {
        return new Request(
            $id,
            $this->login,
            $this->password,
            $destAddr,
            new RequestMessage(
                new RequestMessageType(),
                new RequestMessageData(
                    $text,
                    $this->serviceName
                )
            )
        );
    }

    /**
     * Send SMS.
     * @param string $destAddr
     * @param string $text
     *
     * @return ResponseInterface|null
     */
    public function sendSMS(string $destAddr, string $text): ?ResponseInterface
    {
        return $this->sendSMSWithId($this->getUUID(), $destAddr, $text);
    }

    /**
     * Send SMS with custom ID
     * @param string $id
     * @param string $destAddr
     * @param string $text
     *
     * @return ResponseInterface|null
     */
    public function sendSMSWithId(string $id, string $destAddr, string $text): ?ResponseInterface
    {
        return $this->connector->sendByRest($this->getRequest($id, $destAddr, $text), $this->preSendHook, $this->postSendHook);
    }

    /**
     * @param RestPreSendHook|null $preSendHook
     *
     * @return SMSSender
     */
    public function setPreSendHook(?RestPreSendHook $preSendHook): SMSSender
    {
        $this->preSendHook = $preSendHook;
        return $this;
    }

    /**
     * @param RestPostSendHook|null $postSendHook
     *
     * @return SMSSender
     */
    public function setPostSendHook(?RestPostSendHook $postSendHook): SMSSender
    {
        $this->postSendHook = $postSendHook;
        return $this;
    }
}
