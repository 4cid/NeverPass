<?php

/**
 * Class ConfigTest
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $config = new \NeverPass\Config(__DIR__ . '/../config/conf.sample.yml', new \Symfony\Component\Yaml\Parser());
        $this->assertInstanceOf('NeverPass\Config', $config);
    }

    public function testGoogle_PlusService()
    {
        $config = new \NeverPass\Config(__DIR__ . '/../config/conf.sample.yml', new \Symfony\Component\Yaml\Parser());
        $this->assertTrue(is_array($config->get('Google_PlusService')));
    }
}
 