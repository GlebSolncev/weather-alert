<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\PushTesttNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateNotification extends Component
{
    use InteractsWithBanner;

    public bool $pushStatus = true;

    public bool $emailStatus = true;

    public User $user;

    public function getRules(): array
    {
        return [
            'locations' => ['required', 'array', 'min:1'],
            'locations.*.city' => 'required|string|min:3',
            'locations.*.country' => 'required|string|min:3',
            'locations.*.max_uv' => 'required|decimal:2,4',
            'locations.*.max_precipitation' => 'required|decimal:2,4',
        ];
    }

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->pushStatus = $this->user->push_notification;
        $this->emailStatus = $this->user->email_notification;
    }

    public function render(): View
    {
        return view('livewire.update-notification');
    }

    #[On('toggle-push')]
    public function togglePush(): void
    {
        try {
            $this->pushStatus = ! $this->pushStatus;
            $this->user->update(['push_notification' => $this->pushStatus]);

            if ($this->pushStatus) {
                $this->user->notify(new PushTesttNotification);
            }

            $this->banner('Push notification settings updated successfully');
        } catch (\Exception $e) {
            $this->dangerBanner('Failed to update push notification settings');
        }
    }

    #[On('toggle-email')]
    public function toggleEmail(): void
    {
        try {
            $this->emailStatus = ! $this->emailStatus;
            $this->user->update(['email_notification' => $this->emailStatus]);

            $this->banner('Email notification settings updated successfully');
        } catch (\Exception $e) {
            $this->dangerBanner('Failed to update email notification settings');
        }
    }
}
