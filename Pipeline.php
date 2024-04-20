<?php

namespace Illuminate\Pipeline;

use Closure;

class Pipeline
{
    protected $passable;
    protected $pipes = [];

    public function __construct($passable)
    {
        $this->passable = $passable;
    }

    public function send($passable)
    {
        $this->passable = $passable;

        return $this->then();
    }

    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();

        return $this->then();
    }

    public function then(Closure $destination = null)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->getSlice(),
            $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }

    protected function prepareDestination(Closure $destination = null)
    {
        if (!$destination) {
            return function ($passable) {
                return $passable;
            };
        }

        return $destination;
    }

    protected function getSlice()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                return $pipe($passable, $stack);
            };
        };
    }
}