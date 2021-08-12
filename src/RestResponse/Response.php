<?php

namespace RabotaRu\ZagruzkaConnector\RestResponse;

use RabotaRu\ZagruzkaConnector\Enums\MessageType;

class Response
{
    private const STATUS_NOT_DELIVERY = 5;

    /** @var string */
    private $id;
    /** @var string */
    private $mtNum;
    /** @var int */
    private $status;
    /** @var MessageType */
    private $type;
    /** @var \DateTimeImmutable|null */
    private $doneDate;
    /** @var \DateTimeImmutable */
    private $submitDate;
    /** @var string */
    private $destAddr;
    /** @var string */
    private $sourceAddr;
    /** @var string */
    private $text;
    /** @var string */
    private $partCount;
    /** @var string */
    private $errorCode;
    /** @var string */
    private $mccMnc;
    /** @var integer */
    private $trafficType;

    /**
     * @param array<string, string|int> $arr
     * @throws \Exception
     */
    public function __construct(array $arr)
    {
        $this->id = (string)$arr['id'];
        $this->mtNum = (string)$arr['mtNum'];
        $this->status = (int)$arr['status'];
        $this->type = new MessageType((string)$arr['type']);
        $this->destAddr = (string)$arr['destAddr'];
        $this->sourceAddr = (string)$arr['sourceAddr'];
        $this->text = (string)$arr['text'];
        $this->partCount = (string)$arr['partCount'];
        $this->errorCode = (string)$arr['errorCode'];
        $this->mccMnc = (string)$arr['mccMnc'];
        $this->trafficType = (int)$arr['trafficType'];

        if (!empty($arr['submitDate'])) {
            $this->submitDate = new \DateTimeImmutable((string)$arr['submitDate']);
        }

        if (!empty($arr['doneDate'])) {
            $this->doneDate = new \DateTimeImmutable((string)$arr['doneDate']);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMtNum(): string
    {
        return $this->mtNum;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return MessageType
     */
    public function getType(): MessageType
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDoneDate(): ?\DateTimeImmutable
    {
        return $this->doneDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getSubmitDate(): ?\DateTimeImmutable
    {
        return $this->submitDate;
    }

    /**
     * @return string
     */
    public function getDestAddr(): string
    {
        return $this->destAddr;
    }

    /**
     * @return string
     */
    public function getSourceAddr(): string
    {
        return $this->sourceAddr;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getPartCount(): string
    {
        return $this->partCount;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getMccMnc(): string
    {
        return $this->mccMnc;
    }

    /**
     * @return int
     */
    public function getTrafficType(): int
    {
        return $this->trafficType;
    }

    /**
     * @return bool
     */
    public function isNotDelivery(): bool
    {
        return $this->status == self::STATUS_NOT_DELIVERY;
    }
}
