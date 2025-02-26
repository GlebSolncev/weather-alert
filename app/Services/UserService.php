<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Store a new token for the user
     *
     * @param  array{name: string, token: string, abilities?: array<string>}  $data
     *
     * @throws \Exception
     */
    public function storeToken(User $user, array $data): void
    {
        try {
            Log::info('Creating new token for user', [
                'user_id' => $user->id,
                'token_name' => $data['name'],
            ]);

            $user->tokens()->create($data);

            Log::info('Token created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create token', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
