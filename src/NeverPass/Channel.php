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
    /** @var int */
    private $timestamp = 0;

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
        $this->timestamp = time();
    }

    /**
     * @param $id
     *
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
            $users[$user->getId()] = $user->toArray();
            unset($users[$user->getId()]['id']);
        }
        $locations = array();
        foreach ($this->getLocations() as $location) {
            $locations[] = $location->toArray();
        }
        $return = array(
            'id' => $this->getId(),
            'users' => $users,
            'locations' => $locations,
            'timestamp' => $this->getTimestamp()
        );

        return $return;
    }

    /**
     * @param \Memcached $memcached
     * @param \mysqli    $sql
     */
    public function save(\Memcached $memcached = null, \mysqli $sql = null)
    {
        if (is_null($memcached)) {
            $memcached = new \Memcached;
        }
        $memcached->set('channel-' . $this->getId(), $this);
        if (!is_null($sql)) {
            $sql->query(
                sprintf(
                    "REPLACE INTO `channel` (`id`, `value`) VALUES ('%s', '%s');",
                    $sql->real_escape_string($this->getId()),
                    $sql->real_escape_string(serialize($this))
                )
            );
        }
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
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param            $id
     * @param \Memcached $memcached
     * @param \mysqli    $sql
     *
     * @return Channel
     */
    public static function getCached($id, \Memcached $memcached = null, \mysqli $sql = null)
    {
        if (is_null($memcached)) {
            $memcached = new \Memcached;
        }
        $channel = $memcached->get('channel-' . $id);
        if (is_null($channel) && !is_null($sql)) {
            if ($result = $sql->query(sprintf("SELECT * FROM `channel` WHERE `id` = '%s'", $sql->real_escape_string($id)))) {
                $blob = $result->fetch_assoc();
                if ($blob['value']) {
                    $obj = unserialize($blob['value']);
                    if ($obj instanceof Channel) {
                        $channel = $obj;
                    }
                }
            }

        }
        if (!($channel instanceof Channel)) {
            $channel = new Channel($id);
        }
        return $channel;
    }
}