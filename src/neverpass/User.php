<?php

namespace NeverPass;

/**
 * Class User
 * @package NeverPass
 */
class User
{
    /** @var string */
    private $id = '';

    /** @var string */
    private $displayName = '';

    /** @var string */
    private $imageUrl = '';

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (string)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
}