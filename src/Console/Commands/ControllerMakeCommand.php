<?php

namespace Webberman\Modular\Console\Commands;

use Exception;
use Webberman\Modular\Finder;
use Webberman\Modular\Console\Command;
use Webberman\Modular\Filesystem;
use Webberman\Modular\Str;
use Symfony\Component\Console\Input\InputOption;
use Webberman\Modular\Generators\ControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class ControllerMakeCommand extends SymfonyCommand
{
    use Finder;
    use Command;
    use Filesystem;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource Controller class in a service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws Exception
     */
    public function handle()
    {
        try {
            $controller = ( new ControllerGenerator() )->generate(
                $this->argument('controller'), $this->argument('service'), $this->option('resource')
            );

            $this->info('Controller class created successfully.'.
                "\n".
                "\n".
                'Find it at <comment>'.$controller.'</comment>'."\n"
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
            ['controller', InputArgument::REQUIRED, 'The controller\'s name.'],
            ['service', InputArgument::OPTIONAL, 'The service in which the controller should be generated.'],
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
            ['resource', null, InputOption::VALUE_NONE, 'Generate a resource controller class.'],
        ];
    }

    /**
     * Parse the feature name.
     *  remove the Controller.php suffix if found
     *  we're adding it ourselves.
     *
     * @param string $name
     *
     * @return string
     */
    protected function parseName($name)
    {
        return Str::studly(preg_replace('/Controller(\.php)?$/', '', $name).'Controller');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('plain')) {
            return __DIR__ . '/../Generators/stubs/controller.plain.stub';
        }

        return __DIR__ . '/../Generators/stubs/controller.resource.stub';
    }
}
