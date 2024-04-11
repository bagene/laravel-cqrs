<?php

namespace App\Concerns;

use App\Contracts\Bus\CommandQueryBus;
use App\Contracts\Bus\CommandQueryContract;
use App\Infrastructure\Http\RequestMapper;

/**
 * Trait HandlerTrait
 * @property-read CommandQueryBus $bus
 * @package App\Concerns
 */
trait HandlerTrait
{
    /**
     * @throws \Exception
     */
    public function handle(CommandQueryContract $subject): mixed
    {
        $bus = app(CommandQueryBus::class);
        if (!$bus instanceof CommandQueryBus) {
            throw new \Exception('This class must implement CommandQueryBus');
        }

        return $bus->dispatch($subject);
    }
}
