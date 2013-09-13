<?php

namespace NeverPass;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Yaml\Parser;

/**
 * Class Container
 * @package NeverPass
 * @link    http://pimple.sensiolabs.org/
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
            return new Config(DOCUMENT_ROOT . '/config/conf.yml', new Parser());
        });

        $this['session'] = $this->share(function ($c) {
            $storage = new NativeSessionStorage(array(), new MemcachedSessionHandler(new \Memcached()));
            $session = new Session($storage);
            $session->start();
            return $session;
        });

        $this['request'] = $this->share(function ($c) {
            return Request::createFromGlobals();
        });
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this['config'];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this['session'];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this['request'];
    }
} 