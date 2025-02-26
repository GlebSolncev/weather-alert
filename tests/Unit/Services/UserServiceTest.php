<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService;
    }

    public function test_store_token_creates_token(): void
    {
        $user = User::factory()->create();

        Log::shouldReceive('info')->twice();

        $tokenData = [
            'name' => 'Test Token',
            'token' => 'test_token',
            'abilities' => ['*'],
        ];

        $this->service->storeToken($user, $tokenData);

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'Test Token',
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_store_token_handles_exception(): void
    {
        $user = User::factory()->create();

        Log::shouldReceive('info')->once();
        Log::shouldReceive('error')->once();

        $tokenData = [
            'name' => null, // Это вызовет ошибку
            'token' => 'test_token',
        ];

        $this->expectException(\Exception::class);

        $this->service->storeToken($user, $tokenData);
    }
}
