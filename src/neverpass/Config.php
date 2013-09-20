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
        if ($path === false)
            return $this->yml;

        $levels = explode('.', $path);
        $result = $this->yml;
        foreach ($levels as $value) {
            if (!is_array($result) || !array_key_exists($value, $result)) {
                $result = null;
                break;
            }
            $result = $result[$value];
        }
        return $result;
    }

} 