<?php

namespace NeverPass;

use NeverPass\Exception\LoginException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Yaml\Parser;

/**
 * Class Container
 * @package NeverPass
 * @link    http://pimple.sensiolabs.org/
 * @method \Google_Client getGoogleClient()
 * @method \Symfony\Component\HttpFoundation\Request getRequest()
 * @method \Symfony\Component\HttpFoundation\Session\Session getSession()
 * @method \NeverPass\Config getConfig()
 * @method \NeverPass\User getCurrentUser()
 * @method string getUrl()
 * @method \mysqli|null getMySQL()
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
        $this['config'] = $this->share(function (Container $c) {
            if (!file_exists(DOCUMENT_ROOT . '/config/conf.yml')) throw new \Exception('conf.yml is missing');
            return new Config(DOCUMENT_ROOT . '/config/conf.yml', new Parser());
        });

        $this['session'] = $this->share(function (Container $c) {
            $storage = new NativeSessionStorage(array(), new MemcachedSessionHandler(new \Memcached()));
            $session = new Session($storage);
            $session->start();
            return $session;
        });

        $this['request'] = $this->share(function (Container $c) {
            return Request::createFromGlobals();
        });

        $this['googleclient'] = $this->share(function (Container $c) {
            $conf = $c->getConfig()->get('Google_PlusService');
            if (!$conf) {
                throw new \Exception('Missing Google_PlusService in conf.yml');
            }
            // https://gaeforphp-blog.appspot.com/2013/08/06/using-the-google-apis-client-library-for-php-with-app-engine/
            $client = new \Google_Client(array(
                'ioClass' => 'Google_HttpStreamIO',
                'cacheClass' => 'Google_MemcacheCache',

                'ioMemCacheCache_host' => 'does_not_matter',
                'ioMemCacheCache_port' => '37337',
            ));
            $client->setApplicationName('NeverPass');
            $client->setClientId($conf['ClientId']);
            $client->setClientSecret($conf['ClientSecret']);
            $client->setRedirectUri($c->getUrl() . $conf['RedirectUri']);
            $client->setDeveloperKey($conf['DeveloperKey']);
            return $client;
        });

        $this['currentuser'] = $this->share(function (Container $c) {
            $session = $c->getSession();
            if ($session->has('currentuser')) {
                $user = $session->get('currentuser');
                if ($user instanceof User) {
                    return $user;
                }
            }
            throw new LoginException('No user in session!');
        });

        $this['url'] = $this->share(function (Container $c) {
            $host = $c->getRequest()->getHttpHost();
            // Fix: der GAE Server haut am Ende ein : hin.
            if ($host[strlen($host) - 1] == ':') {
                $host = substr($host, 0, -1);
            }
            return $c->getRequest()->getScheme() . '://' . $host;
        });

        $this['isUserLoggedIn'] = function (Container $c) {
            return $c->getSession()->has('currentuser') && ($c->getSession()->get('currentuser') instanceof User);
        };

        $this['mysql'] = $this->share(function (Container $c) {
            $config = $c->getConfig();
            if (!$config->get('Database')) return null;
            return new \mysqli(
                $config->get('Database.host'),
                $config->get('Database.username'),
                $config->get('Database.password'),
                $config->get('Database.dbname'),
                $config->get('Database.port'),
                $config->get('Database.socket')
            );
        });
    }

    /**
     * @return bool
     */
    public function isUserLoggedIn()
    {
        return $this['isUserLoggedIn'];
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    function __call($name, $arguments)
    {
        $offset = strtolower(substr($name, 3));
        if ($this->offsetExists($offset)) {
            return $this[$offset];
        }
        throw new \Exception('Call to undefined method.');
    }
} 