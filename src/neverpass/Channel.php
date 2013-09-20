<?php

namespace NeverPass;


class Channel
{
    /** @var bool|string */
    private $id = false;
    /** @var User[] */
    private $users = array();
    /** @var Location[] */
    private $locations = array();

    function __construct($id = false)
    {
        if ($id === false || strlen($id) === 0) {
            $id = $this->getGeneratedId();
        } else {
            if (!$this->isIdValid($id)) {
                throw new \Exception(sprintf('Id is not valid: "%s"', $id));
            }
        }
        $this->id = $id;
    }

    /**
     * @param $id
     * @return bool
     */
    private function isIdValid($id)
    {
        return strlen($id) == 32;
    }

    /**
     * @return string
     */
    private function getGeneratedId()
    {
        return md5(microtime());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $users = array();
        foreach ($this->getUsers() as $user) {
            $users[] = $user->toArray();
        }
        $locations = array();
        foreach ($this->getLocations() as $location) {
            $locations[] = $location->toArray();
        }
        $return = array(
            'id' => $this->getId(),
            'users' => $users,
            'locations' => $locations
        );

        return $return;
    }

    public function save(\Memcached $memcached = null)
    {
        if (is_null($memcached)) {
            $memcached = new \Memcached;
        }
        $memcached->set('channel-' . $this->getId(), $this);
    }

    /**
     * @return bool|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \NeverPass\User $user
     */
    public function addUser($user)
    {
        $this->users[$user->getId()] = $user;
    }

    /**
     * @return \NeverPass\User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param \NeverPass\Location $location
     */
    public function addLocation($location)
    {
        $this->locations[$location->getUserId()] = $location;
    }

    /**
     * @return \NeverPass\Location[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param $id
     * @param \Memcached $memcached
     * @return Channel
     */
    public static function getCached($id, \Memcached $memcached = null)
    {
        if (is_null($memcached)) {
            $memcached = new \Memcached;
        }
        $channel = $memcached->get('channel-' . $id);
        if (!($channel instanceof Channel)) {
            $channel = new Channel($id);
        }
        return $channel;
    }
}