<?php

namespace NeverPass;

/**
 * Class Config
 * @package NeverPass
 * TODO:
 */
class Config
{
    private $filePath;
    private $yml = array();

    /**
     * @param                                $filePath
     * @param \Symfony\Component\Yaml\Parser $parser
     */
    function __construct($filePath, \Symfony\Component\Yaml\Parser $parser)
    {
        $this->filePath = $filePath;
        $this->yml = $parser->parse(file_get_contents($filePath));
    }

    /**
     * @param bool $path
     *
     * @return array|mixed
     */
    public function get($path = false)
    {
        return $path !== false ? $this->yml[$path] : $this->yml;
    }

} 