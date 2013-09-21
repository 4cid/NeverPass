<?php

namespace NeverPass\tool;

/**
 * Class Memcached
 * For testing...
 * @package NeverPass\tool
 */
class Memcached extends \Memcached
{
    public function get($key, $cache_cb = null, &$cas_token = null)
    {
        $value = parent::get($key, $cache_cb, $cas_token);
        if ($value !== null) {
            return unserialize(gzinflate($value));
        }
        return $value;
    }

    public function set($key, $value, $expiration = null)
    {
        $value = gzdeflate(serialize($value), 9);
        parent::set($key, $value, $expiration);
    }


} 