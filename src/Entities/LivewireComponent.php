<?php

namespace Webberman\Modular\Entities;

class LivewireComponent extends Entity
{
    public function __construct($classPath, $viewPath, $inline = false)
    {
        $this->setAttributes([
            'classPath' => $classPath,
            'viewPath' => $viewPath,
            'inline' => $inline,
        ]);
    }
}
