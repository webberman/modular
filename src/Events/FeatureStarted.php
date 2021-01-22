<?php

namespace Webberman\Modular\Events;

class FeatureStarted
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $arguments;

    /**
     * FeatureStarted constructor.
     * @param  string  $name
     * @param  array  $arguments
     */
    public function __construct($name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }
}
