<?php

namespace NeverPass;

/**
 * Class Location
 * @package NeverPass
 */
class Location
{
    /** @var  float */
    private $longitude;
    /** @var  float */
    private $latitude;
    /** @var  int */
    private $heading;
    /** @var  string */
    private $userId;
    /** @var  int */
    private $timestamp;
    /** @var  int */
    private $accuracy;

    /**
     * @param int    $heading
     * @param float  $latitude
     * @param float  $longitude
     * @param int    $accuracy
     * @param string $userId
     */
    function __construct($heading, $latitude, $longitude, $accuracy, $userId)
    {
        $this->heading = (int)$heading;
        $this->latitude = (float)$latitude;
        $this->longitude = (float)$longitude;
        $this->accuracy = (int)$accuracy;
        $this->userId = (string)$userId;
        $this->timestamp = time();
    }

    /**
     * @return int
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5(
            json_encode(
                array(
                    $this->getLatitude(),
                    $this->getLatitude(),
                    $this->getAccuracy(),
                    $this->getHeading(),
                    $this->getUserId(),
                )
            )
        );
    }
} 