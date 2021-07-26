<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

final class RequestMessage implements \JsonSerializable
{
    /**
     * @var RequestMessageType
     */
    private RequestMessageType $type;
    /**
     * @var RequestMessageData
     */
    private RequestMessageData $data;

    public function __construct(RequestMessageType $type, RequestMessageData $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return RequestMessageType
     */
    public function getType(): RequestMessageType
    {
        return $this->type;
    }

    /**
     * @param RequestMessageType $type
     *
     * @return RequestMessage
     */
    public function setType(RequestMessageType $type): RequestMessage
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
