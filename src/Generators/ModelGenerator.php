<?php

namespace Webberman\Modular\Generators;

use Exception;
use Webberman\Modular\Str;
use Webberman\Modular\Entities\Model;

class ModelGenerator extends Generator
{
    /**
     * Generate the file.
     *
     * @param $name
     * @return Model|bool
     * @throws Exception
     */
    public function generate($name)
    {
        $model = Str::model($name);
        $path = $this->findModelPath($model);

        if ($this->exists($path)) {
            throw new Exception('Model already exists');

            return false;
        }

        $namespace = $this->findModelNamespace();

        $content = file_get_contents($this->getStub());
        $content = str_replace(
            ['{{model}}', '{{namespace}}', '{{unit_namespace}}'],
            [$model, $namespace, $this->findUnitNamespace()],
            $content
        );

        $this->createFile($path, $content);

        return new Model(
            $model,
            $namespace,
            basename($path),
            $path,
            $this->relativeFromReal($path),
            $content
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__ . '/../Generators/stubs/model.stub';
    }
}
