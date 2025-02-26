<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Vapid extends Component
{
    public string $vapidPublicKey = '';

    public ?User $user;

    public function render()
    {
        return view('livewire.vapid');
    }

    public function mount(): void
    {
        $this->vapidPublicKey = config('webpush.vapid.public_key');
        $this->user = Auth::user();
    }

    #[On('init-token')]
    public function initToken(
        string $endpoint,
        string $publicKey,
        string $authToken,
    ): void {
        try {
            Log::info('Initializing push notification token', [
                'user_id' => $this->user?->id,
            ]);

            $this->user?->updatePushSubscription($endpoint, $publicKey, $authToken);

            Log::info('Push notification token initialized successfully');
        } catch (\Exception $e) {
            Log::error('Failed to initialize push notification token', [
                'error' => $e->getMessage(),
                'user_id' => $this->user?->id,
            ]);
            throw $e;
        }
    }
}
