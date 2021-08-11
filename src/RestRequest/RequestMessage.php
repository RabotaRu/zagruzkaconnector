<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

use RabotaRu\ZagruzkaConnector\Enums\MessageType;

final class RequestMessage implements \JsonSerializable
{
    /**
     * @var MessageType
     */
    private $type;
    /**
     * @var RequestMessageData
     */
    private $data;

    public function __construct(MessageType $type, RequestMessageData $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return MessageType
     */
    public function getType(): MessageType
    {
        return $this->type;
    }

    /**
     * @param MessageType $type
     *
     * @return RequestMessage
     */
    public function setType(MessageType $type): RequestMessage
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return RequestMessageData
     */
    public function getData(): RequestMessageData
    {
        return $this->data;
    }

    /**
     * @param RequestMessageData $data
     *
     * @return RequestMessage
     */
    public function setData(RequestMessageData $data): RequestMessage
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
