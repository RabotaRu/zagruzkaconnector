<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

final class RequestMessageData implements \JsonSerializable
{
    /** @var string  */
    private $text;
    /** @var string  */
    private $serviceNumber;
    /** @var string|null  */
    private $externalUserId = null;
    /** @var bool  */
    private $flash = false;
    /** @var int  */
    private $ttl = 0;
    /** @var \RabotaRu\ZagruzkaConnector\RestRequest\RequestMessageDataTtlUnit  */
    private $ttlUnit;

    public function __construct(string $text, string $serviceNumber)
    {
        $this->ttlUnit = new RequestMessageDataTtlUnit();
        $this->text = $text;
        $this->serviceNumber = $serviceNumber;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return RequestMessageData
     */
    public function setText(string $text): RequestMessageData
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceNumber(): string
    {
        return $this->serviceNumber;
    }

    /**
     * @param string $serviceNumber
     *
     * @return RequestMessageData
     */
    public function setServiceNumber(string $serviceNumber): RequestMessageData
    {
        $this->serviceNumber = $serviceNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalUserId(): ?string
    {
        return $this->externalUserId;
    }

    /**
     * @param string|null $externalUserId
     *
     * @return RequestMessageData
     */
    public function setExternalUserId(?string $externalUserId): RequestMessageData
    {
        $this->externalUserId = $externalUserId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFlash(): bool
    {
        return $this->flash;
    }

    /**
     * @param bool $flash
     *
     * @return RequestMessageData
     */
    public function setFlash(bool $flash): RequestMessageData
    {
        $this->flash = $flash;
        return $this;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     *
     * @return RequestMessageData
     */
    public function setTtl(int $ttl): RequestMessageData
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * @return RequestMessageDataTtlUnit
     */
    public function getTtlUnit(): RequestMessageDataTtlUnit
    {
        return $this->ttlUnit;
    }

    /**
     * @param RequestMessageDataTtlUnit $ttlUnit
     *
     * @return RequestMessageData
     */
    public function setTtlUnit(RequestMessageDataTtlUnit $ttlUnit): RequestMessageData
    {
        $this->ttlUnit = $ttlUnit;
        return $this;
    }


    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        if (null === $this->externalUserId) {
            unset($arr['externalUserId']);
        }
        if (!$this->flash) {
            unset($arr['flash']);
        }
        if (0 === $this->ttl) {
            unset($arr['ttl'], $arr['ttlUnit']);
        }
        return $arr;
    }
}
