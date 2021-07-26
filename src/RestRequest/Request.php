<?php
declare(strict_types=1);

namespace RabotaRu\ZagruzkaConnector\RestRequest;

final class Request implements \JsonSerializable
{
    private string $id;
    private string $login;
    private string $password;
    private string $destAddr;

    private bool $useTimeDiff = false;
    private ?bool $shortenLinks = null;
    private RequestRegisteredDelivery $delivery;

    private RequestMessage $message;

    public function __construct(
        string $id,
        string $login,
        string $password,
        string $destAddr,
        RequestMessage $message
    ) {
        $this->delivery = new RequestRegisteredDelivery();
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->destAddr = $destAddr;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Request
     */
    public function setId(string $id): Request
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return Request
     */
    public function setLogin(string $login): Request
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return Request
     */
    public function setPassword(string $password): Request
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestAddr(): string
    {
        return $this->destAddr;
    }

    /**
     * @param string $destAddr
     *
     * @return Request
     */
    public function setDestAddr(string $destAddr): Request
    {
        $this->destAddr = $destAddr;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseTimeDiff(): bool
    {
        return $this->useTimeDiff;
    }

    /**
     * @param bool $useTimeDiff
     *
     * @return Request
     */
    public function setUseTimeDiff(bool $useTimeDiff): Request
    {
        $this->useTimeDiff = $useTimeDiff;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getShortenLinks(): ?bool
    {
        return $this->shortenLinks;
    }

    /**
     * @param bool|null $shortenLinks
     *
     * @return Request
     */
    public function setShortenLinks(?bool $shortenLinks): Request
    {
        $this->shortenLinks = $shortenLinks;
        return $this;
    }

    /**
     * @return RequestRegisteredDelivery
     */
    public function getDelivery(): RequestRegisteredDelivery
    {
        return $this->delivery;
    }

    /**
     * @param RequestRegisteredDelivery $delivery
     *
     * @return Request
     */
    public function setDelivery(RequestRegisteredDelivery $delivery): Request
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * @return RequestMessage
     */
    public function getMessage(): RequestMessage
    {
        return $this->message;
    }

    /**
     * @param RequestMessage $message
     *
     * @return Request
     */
    public function setMessage(RequestMessage $message): Request
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        if (null === $this->shortenLinks) {
            unset($arr['shortenLinks']);
        }
        return $arr;
    }
}
