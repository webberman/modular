<?php

namespace Webberman\Modular\Console\Commands;

use Webberman\Modular\Finder;
use Webberman\Modular\Filesystem;
use Webberman\Modular\Console\Command;
use Webberman\Modular\Generators\MicroGenerator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class InitMicroCommand extends SymfonyCommand
{
    use Finder;
    use Command;
    use Filesystem;
    use InitCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'init:micro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Webberman\Modular Micro in current project.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $version = app()->version();
        $this->info("Initializing Webberman\Modular Micro for Laravel $version\n");

        $generator = new MicroGenerator();
        $paths = $generator->generate();

        $this->comment("Created directories:");
        $this->comment(join("\n", $paths));

        $this->welcome();

        return 0;
    }
}
