<?php

namespace Webberman\Modular\Bus;

use ReflectionException;
use Illuminate\Support\Collection;
use Webberman\Modular\Events\FeatureStarted;
use Illuminate\Foundation\Bus\DispatchesJobs;

trait ServesFeatures
{
    use Marshal;
    use DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param string $feature
     * @param array $arguments
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function serve($feature, $arguments = [])
    {
        event(new FeatureStarted($feature, $arguments));

        return $this->dispatch($this->marshal($feature, new Collection(), $arguments));
    }
}
