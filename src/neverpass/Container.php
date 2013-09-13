<?php

namespace NeverPass;

/**
 * Class Container
 * @package NeverPass
 */
class Container extends \Pimple
{

    /**
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        // Setup Config
        $this['config'] = $this->share(function ($c) {
            if (!file_exists(DOCUMENT_ROOT . '/config/conf.yml')) throw new \Exception('conf.yml is missing');
            return new \NeverPass\Config(DOCUMENT_ROOT . '/config/conf.yml', new \Symfony\Component\Yaml\Parser);
        });
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this['config'];
    }
} 