<?php

namespace Webberman\Modular\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Webberman\Modular\Generators\Generator;
use Illuminate\Console\GeneratorCommand as IlluminateGeneratorCommand;

class GeneratorCommand extends IlluminateGeneratorCommand
{
    /**
     * @var Generator
     */
    private $generator;

    public function __construct(Filesystem $files, Generator $generator)
    {
        parent::__construct($files);

        $this->generator = $generator;
    }

    public function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
