<?php

namespace App\Http\Controllers;

use App\Concerns\HandlerTrait;
use App\Infrastructure\Http\RequestMapper;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use HandlerTrait;
    public function __construct(private readonly RequestMapper $requestMapper)
    {
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): void
    {
        $command = $this->requestMapper->fromBody($request, \App\Command\TestUser\TestUserCommand::class);

        $this->handle($command);
    }
}
