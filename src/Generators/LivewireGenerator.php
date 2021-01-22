<?php

namespace Webberman\Modular\Generators;

use Exception;
use Webberman\Modular\Str;
use Webberman\Modular\Entities\LivewireComponent;

class LivewireGenerator extends Generator
{
    /**
     * Generate the file.
     *
     * @param string $name
     * @param string $service
     * @param boolean $inline
     *
     * @return LivewireComponent|bool
     * @throws Exception
     */
    public function generate($name, $service, $inline = false)
    {
        $class      = Str::livewireClass($name);
        $view       = Str::livewireView($name);
        $service    = Str::domain($service);
        $namespace  = $this->findLivewireNamespace($service, $class);

        $classPath = $this->findLivewireComponentPath($service, $class);
        $viewPath  = $this->findLivewireViewPath($service, $view);

        if ( $this->exists($classPath) || $this->exists($viewPath)) {
            throw new Exception('Livewire component already exists');
        }

        $classContent = str_replace(
            ['{{class}}', '{{namespace}}', '{{unit_namespace}}', '{{view}}'],
            [basename($class), $namespace, $this->findUnitNamespace(), $this->findView($service, $viewPath)],
            file_get_contents($this->getClassStub($inline))
        );

        $this->createFile($classPath, $classContent);

        if( ! $inline ) {
            $this->createFile($viewPath, file_get_contents($this->getViewStub()));
        }

        return new LivewireComponent(
            $this->relativeFromReal($classPath),
            $this->relativeFromReal($viewPath),
            $inline
        );
    }

    /**
     * Get the stub file for the livewire class.
     *
     * @param $inline 'Determines whether to return the inline component'
     * @return string
     */
    public function getClassStub($inline)
    {
        if ($inline) {
            return __DIR__ . '/stubs/livewire.inline.stub';
        }

        return __DIR__ . '/stubs/livewire.stub';
    }

    /**
     * Get the stub file for the livewire view.
     *
     * @return string
     */
    public function getViewStub()
    {
        return __DIR__ . '/stubs/livewire.view.stub';
    }
}
