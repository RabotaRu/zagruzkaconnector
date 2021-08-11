<?php

namespace RabotaRu\ZagruzkaConnector\RestResponse;

class Response
{
    /** @var string */
    private $id;
    /** @var string */
    private $mtNum;
    /** @var int */
    private $status;
    /** @var string */
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

    public function fillByArray(array $arr): Response
    {
        $this->id = $arr['id'];
        $this->mtNum = $arr['mtNum'];
        $this->status = $arr['status'];
//        $this->type
        return $this;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDoneDate(): \DateTimeImmutable
    {
        return $this->doneDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getSubmitDate(): \DateTimeImmutable
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

}