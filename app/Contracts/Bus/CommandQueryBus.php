<?php

namespace App\Contracts\Bus;

interface CommandQueryBus
{
    public function dispatch(CommandQueryContract $command): mixed;
}
