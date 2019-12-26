<?php

namespace Pleets\Jose;

use Pleets\Jose\Unicode\Helper;

class JWS
{
    protected $header;

    protected $payload;

    public function __construct($header, $payload)
    {
        $this->header = $header;
        $this->payload = $payload;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getEncodedHeader(): string
    {
        return Helper::toBase64Url(base64_encode($this->header));
    }

    public function getEncodedPayload()
    {
        return Helper::toBase64Url(base64_encode($this->payload));
    }

    public function getSigningInputValue()
    {
        return $this->getEncodedHeader() . '.' . $this->getEncodedPayload();
    }

    public function build($key): string
    {
        $json = json_decode($key);
        $key = Helper::addPadding(Helper::toBase64($json->k));
        $hmac = hash_hmac('sha256', $this->getSigningInputValue(), base64_decode($key), true);
        return $this->getSigningInputValue() .'.'. Helper::toBase64Url(base64_encode($hmac));
    }
}