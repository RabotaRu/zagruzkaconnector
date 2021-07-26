<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

use InvalidArgumentException;

final class RequestMessageDataTtlUnit implements \JsonSerializable
{
    public const SECONDS = "SECONDS",
        MINUTES = "MINUTES",
        HOURS = "HOURS";

    /**
     * @var string
     */
    private string $ttlUint;

    /**
     * RequestMessageDataTtlUnit constructor.
     *
     * @param string $ttlUint
     * @throws \InvalidArgumentException
     */
    public function __construct(string $ttlUint = self::MINUTES)
    {
        switch ($ttlUint) {
            case self::MINUTES:
            case self::HOURS:
            case self::SECONDS:
                $this->ttlUint = $ttlUint;
                break;
            default:
                throw new InvalidArgumentException("Param ttlUnit must be SECONDS, MINUTES or HOURS");
        }
    }

    public function getTtlUnit(): string
    {
        return $this->ttlUint;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->ttlUint;
    }
}
