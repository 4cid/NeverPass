<?php

namespace NeverPass;

use Symfony\Component\Yaml\Parser;

/**
 * Class Config
 * @package NeverPass
 */
class Config
{
    private $filePath;
    private $yml = array();

    /**
     * @param string $filePath
     * @param Parser $parser
     */
    function __construct($filePath, Parser $parser)
    {
        $this->filePath = $filePath;
        $this->yml = $parser->parse(file_get_contents($filePath));
    }

    /**
     * @param bool|string $path
     *
     * @return mixed
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