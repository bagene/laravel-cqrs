<?php

namespace App\Command\TestUser;

final class TestUserCommandHandler
{
    public function __invoke(TestUserCommand $command): void
    {
        dd($command->toArray());
    }
}
