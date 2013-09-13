<?php

namespace NeverPass;

/**
 * Class Container
 * @package NeverPass
 */
class Container extends \Pimple {

    public function __construct(array $values = array())
    {
        parent::__construct($values);
    }

} 