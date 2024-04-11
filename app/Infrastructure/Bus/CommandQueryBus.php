<?php

namespace App\Infrastructure\Bus;

use App\Contracts\Bus\CommandQueryBus as CommandBusContract;
use App\Contracts\Bus\CommandQueryContract;
use App\Contracts\CommandQueryInvokable;
use Illuminate\Support\Facades\App;

class CommandQueryBus implements CommandBusContract
{
    public function dispatch(CommandQueryContract $command): mixed
    {
        $reflection = new \ReflectionClass($command);
        $handlerName = $reflection->getShortName() . 'Handler';
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());

        /** @var CommandQueryInvokable $handler */
        $handler = App::make($handlerName);
        return $handler->__invoke($command);
    }
}
