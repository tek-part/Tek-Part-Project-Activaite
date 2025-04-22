<?php

namespace App\Console\Commands;

use App\Http\Middleware\CheckLicensePermission;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TestLicenseMiddleware extends Command
{
    protected $signature = 'test:license-middleware {user_id : User ID to test} {permission : Permission to test}';
    protected $description = 'Test the license middleware for a user and permission';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $permission = $this->argument('permission');

        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $this->info("Testing permission '{$permission}' for user: {$user->name}");

        // Create dummy request
        $request = Request::create('/test', 'GET');

        // Set up Auth facade to return our user
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Set up dummy next closure
        $next = function ($request) {
            return new Response('Access granted');
        };

        try {
            // Create middleware instance
            $middleware = new CheckLicensePermission();

            // Run middleware
            $response = $middleware->handle($request, $next, $permission);

            $this->info("Middleware allowed access: " . $response->getContent());
            return 0;
        } catch (\Exception $e) {
            $this->error("Middleware denied access: " . $e->getMessage());
            return 1;
        }
    }
}
