<?php

namespace App\Contracts;

use App\Contracts\Bus\CommandQueryContract;

interface CommandQueryInvokable
{
    public function __invoke(CommandQueryContract $command): mixed;
}
