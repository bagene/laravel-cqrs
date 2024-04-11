<?php

namespace App\Command\TestUser;

use App\Infrastructure\Command\AbstractCommandQuery;

class TestUserCommand extends AbstractCommandQuery
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param array<string|mixed> $data
     */
    public function __construct(private readonly string $firstName, private readonly string $lastName, private readonly array $data = [])
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    /** @return array<string|mixed> */
    public function getData(): array
    {
        return $this->data;
    }
}
