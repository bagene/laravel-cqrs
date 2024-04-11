<?php

namespace App\Concerns;

trait CommandDispatchable
{
    public function dispatch()
    {
        $this->commandBus->dispatch($this);
    }
}
