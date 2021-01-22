<?php

namespace Webberman\Modular\Console\Commands;

use Exception;
use Webberman\Modular\Finder;
use Webberman\Modular\Filesystem;
use Webberman\Modular\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Webberman\Modular\Generators\LivewireGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class LivewireMakeCommand extends SymfonyCommand
{
    use Finder;
    use Command;
    use Filesystem;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:livewire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Livewire component in a domain';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Livewire';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        try {
            $livewire = ( new LivewireGenerator() )->generate(
                $this->argument('name'),
                $this->argument('service'),
                $this->option('inline')
            );

            $this->info('Livewire component created successfully.' .
                "\n" .
                "\n" .
                'Find the class at <comment>' . $livewire->classPath . '</comment>' . "\n" .
                ($this->option('inline')?  '' : 'Find the view at <comment>' . $livewire->viewPath . '</comment>' . "\n")
            );

        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the livewire component.'],
            ['service', InputArgument::REQUIRED, 'The Service in which this livewire component should be generated.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['inline', null, InputOption::VALUE_NONE, 'Generate an inline component class.'],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('inline')) {
            return __DIR__ . '/../Generators/stubs/livewire.inline.stub';
        }

        return __DIR__ . '/../Generators/stubs/livewire.stub';
    }
}
