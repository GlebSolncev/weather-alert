<x-action-section>
    <x-slot name="title">
        Notification settings
    </x-slot>

    <x-slot name="description">
        Turn on or off notifications(email, push) for your account
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            Enable or disable Push notification weather alert
        </div>

        <div class="col-span-6 sm:col-span-4">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="Push notification" />
                    <x-button @click="$dispatch('toggle-push')" class="{{ $pushStatus  ? 'bg-indigo-500' : 'bg-red-700' }} }} mt-2">{{ $pushStatus ? 'enabled' : 'disabled' }}</x-button>
            </div>
        </div>

        <div class="mt-5"></div>

        <div class="max-w-xl text-sm text-gray-600">
            Enable or disable email notification youre account
        </div>

        <div class="col-span-6 sm:col-span-4">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="Email notification" />
                <x-button @click="$dispatch('toggle-email')" class="{{ $emailStatus ? 'bg-indigo-500' : 'bg-red-700' }} mt-2">{{ $emailStatus ? 'enabled' : 'disabled' }}</x-button>
            </div>
        </div>
    </x-slot>
</x-action-section>



