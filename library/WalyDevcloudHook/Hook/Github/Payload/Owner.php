<?php
namespace WalyDevcloudHook\Hook\Github\Payload;

class Owner implements PayloadInterface
{
    protected $name = null;
    protected $email = null;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
