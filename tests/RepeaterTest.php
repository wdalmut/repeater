<?php
namespace Repeater;

class RepeaterTest extends \PHPUnit_Framework_TestCase
{
    public function testRepeatCallbackWithOk()
    {
        $r = new Repeater();
        $ok = false;
        $r->until(function($context) use (&$ok){
            $ok = true;
            $context->ok();
        });
        $this->assertTrue($ok);
    }

    public function testReapetCallbackWithFailure()
    {
        $r = new Repeater();
        $times = 0;
        $ok = false;

        $r->until(function($context) use (&$times, &$ok){
            if ($times++ == 0) {
                $context->fail();
            } else {
                $ok = true;
                $context->ok();
            }
        });

        $this->assertGreaterThan(0, $times);
        $this->assertTrue($ok);
    }

    public function testExceptionAreSuppressed()
    {
        $r = new Repeater();
        $times = 0;
        $ok = false;

        $r->until(function($context) use (&$times, &$ok){
            if ($times++ == 0) {
                throw new \RuntimeException("Invalid");
            } else {
                $ok = true;
                $context->ok();
            }
        });

        $this->assertGreaterThan(0, $times);
        $this->assertTrue($ok);
    }

    public function testReturnValuesArePreserved()
    {
        $r = new Repeater();
        $ok = false;
        $ret = $r->until(function($context) use (&$ok){
            $ok = true;
            $context->ok();
            return "hello";
        });
        $this->assertEquals("hello", $ret);
    }

    public function testReturnValuesArePreservedOnFailures()
    {
        $r = new Repeater();
        $times = 0;
        $ok = false;

        $ret = $r->until(function($context) use (&$times, &$ok){
            if ($times++ == 0) {
                $context->fail();
            } else {
                $ok = true;
                $context->ok();
                return "hello";
            }
        });

        $this->assertGreaterThan(0, $times);
        $this->assertEquals("hello", $ret);
    }

    public function testExitOnMaxTries()
    {
        $r = new Repeater(3);
        $times = 0;
        $ok = false;

        $ret = $r->until(function($context) use (&$times, &$ok){
            $times++;
            $context->fail();
        });

        $this->assertEquals(3, $times); // post-inc
    }

    public function testErrorReturnValues()
    {
        $r = new Repeater(3);
        $times = 0;
        $ok = false;

        $ret = $r->until(function($context) use (&$times, &$ok){
            $times++;
            $context->fail();

            return "nothing";
        });

        $this->assertEquals(3, $times); // post-inc
        $this->assertEquals("nothing", $ret);
    }

    public function testDefaultContextIsAFailedContext()
    {
        $r = new Repeater(3);
        $times = 0;
        $ok = false;

        $ret = $r->until(function($context) use (&$times, &$ok){
            $times++;
            return "nothing";
        });

        $this->assertEquals(3, $times); // post-inc
        $this->assertEquals("nothing", $ret);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionArePropagatedAtTheEnd()
    {
        $r = new Repeater(3);
        $times = 0;
        $ok = false;

        $ret = $r->until(function($context) use (&$times, &$ok){
            throw new \RuntimeException("OK");
        });
    }
}
