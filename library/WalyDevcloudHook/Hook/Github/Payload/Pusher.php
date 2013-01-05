<?php
namespace WalyDevcloudHook\Hook\Github\Payload;

class Pusher implements PayloadInterface
{
    protected $name = null;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

