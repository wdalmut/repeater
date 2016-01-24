<?php
namespace Repeater;

class Repeater
{
    private $times;

    public function __construct($times = 5)
    {
        $this->times = $times;
    }

    public function until(callable $invokable, $context = null)
    {
        if (!$context) {
            $context = new Context();
        }

        try {
            $context->inc();
            $ret = call_user_func($invokable, $context);
        } catch (\Exception $e) {
        } finally {
            if ((!$context->isValid()) && $context->getTimes() < ($this->times)) {
                $ret = $this->until($invokable, $context);
            }

            if (($context->getTimes() >= ($this->times)) && isset($e)) {
                throw $e;
            }

        }

        return $ret;
    }
}
