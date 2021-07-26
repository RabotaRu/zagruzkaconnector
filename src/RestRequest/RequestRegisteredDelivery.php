<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

use InvalidArgumentException;

final class RequestRegisteredDelivery implements \JsonSerializable
{
    public const DISABLED = 0,
        ENABLED = 1,
        ENABLED_NOT_DELIVERED_ONLY = 2;

    /**
     * @var int
     */
    private int $val;

    public function __construct(int $val = self::ENABLED)
    {
        switch ($val) {
            case self::ENABLED:
            case self::DISABLED:
            case self::ENABLED_NOT_DELIVERED_ONLY:
                $this->val = $val;
                break;
            default:
                throw new InvalidArgumentException("Param val must be 0, 1 or 2");
        }
    }

    public function get(): int
    {
        return $this->val;
    }

    /**
     * @return int
     */
    public function jsonSerialize()
    {
        return $this->val;
    }
}
