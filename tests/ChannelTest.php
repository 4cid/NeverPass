<?php

/**
 * Class ChannelTest
 */
class ChannelTest extends PHPUnit_Framework_TestCase
{
    public function testSelfGeneratedId()
    {
        $channel = new \NeverPass\Channel();
        $this->assertTrue(strlen($channel->getId()) == 32);
    }

    /**
     * @expectedException Exception
     */
    public function testWrongId()
    {
        new \NeverPass\Channel('wrong id');
    }
}
 