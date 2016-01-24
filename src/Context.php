<?php
namespace Repeater;

class Context
{
    private $valid;
    private $times;

    public function __construct()
    {
        $this->valid = false;
        $this->times = 0;
    }

    public function ok()
    {
        $this->valid = true;
    }

    public function fail()
    {
        $this->valid = false;
    }

    public function inc()
    {
        $this->times++;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getTimes()
    {
        return $this->times;
    }
}
