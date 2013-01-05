<?php
namespace WalyDevcloudHook\Hook\Github\Adapter;

use WalyDevcloudHook\Hook\Github\Payload;

class PayloadAdapter implements AdapterInterface
{
    protected $payload = null;

    public function __construct($jsonPayload)
    {
        $payloadArray = json_decode($jsonPayload);

        $this->payload = new Payload();
    }

    public function parse()
    {
        return $this->payload;
    }
}
