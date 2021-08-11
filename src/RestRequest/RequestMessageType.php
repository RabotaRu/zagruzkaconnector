<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

use InvalidArgumentException;

final class RequestMessageType implements \JsonSerializable
{
    public const SMS = "SMS",
        PUSH = "PUSH",
        VIBER = "VIBER",
        VK = "VK",
        WHATSAPP = "WHATSAPP",
        FLASHING_CALL = "FLASHINGCALL";

    /**
     * @var string
     */
    private $type;

    /**
     * RequestMessageType constructor.
     *
     * @param string $type
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $type = self::SMS)
    {
        switch ($type) {
            case self::VIBER:
            case self::WHATSAPP:
                throw new InvalidArgumentException("Message type $type is unsupported now, 
                coming in future versions");
            case self::SMS:
            case self::VK:
            case self::PUSH:
            case self::FLASHING_CALL:
                $this->type = $type;
                break;
            default:
                throw new InvalidArgumentException("Param type is invalid, must be only 
                PUSH, SMS, VIBER, VK, WHATSAPP or FLASHINGCALL.");
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->type;
    }
}
