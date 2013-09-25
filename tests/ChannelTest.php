<?php

/**
 * Class ChannelTest
 */
class ChannelTest extends PHPUnit_Framework_TestCase
{
    public function testSelfGeneratedId()
    {
        $channel = new \NeverPass\Channel();
        $this->assertTrue(strlen($channel->getId()) > 0);
    }

    /**
     * @expectedException Exception
     */
    public function testWrongId()
    {
        new \NeverPass\Channel('wrong id');
    }

    public function testRightId()
    {
        $channel = new \NeverPass\Channel();
        $channel = new \NeverPass\Channel($channel->getId());
        $this->assertInstanceOf('\NeverPass\Channel', $channel);
    }
}
 