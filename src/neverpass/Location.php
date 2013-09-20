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


    /**
     * @param int $heading
     * @param float $latitude
     * @param float $longitude
     * @param string $userId
     */
    function __construct($heading, $latitude, $longitude, $userId)
    {
        $this->heading = (int)$heading;
        $this->latitude = (float)$latitude;
        $this->longitude = (float)$longitude;
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
} 