<x-location-section submit="updateLocation">
    <x-slot name="title">
        {{ __('Update Location') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add or update list your cities') }}
    </x-slot>

    <x-slot name="form">
        <div>
            @if(count($locations) === 0)
                Please add location
            @endif

            @foreach ($locations as $index => $location)
                <div class="flex flex-row" wire:key="locations.{{ $index }}.city">
                    <div class="columns-xs">
                        <x-input
                                id="country"
                                type="text"
                                placeholder="Country"
                                class="mt-1 block w-full text-center {{ isset($error[$index]['message']) ? 'text-red-600' : '' }}"
                                wire:model.live="locations.{{ $index }}.country" />
                        <x-input-error for="country" class="mt-2" />
                        @error('locations.' . $index . '.country') <span class="error text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="columns-xs ml-3">
                        <x-input
                                id="city"
                                type="text"
                                placeholder="City"
                                class="mt-1 block w-full text-center {{ isset($error[$index]['message']) ? 'text-red-600' : '' }}"
                                wire:model.live="locations.{{ $index }}.city" />
                        <x-input-error for="city" class="mt-2" />
                        @error('locations.' . $index . '.city') <span class="error text-red-600">{{ $message }}</span> @enderror
                    </div>


                    <div class="columns-xs ml-3">
                        <x-input
                                id="max_uv"
                                type="number"
                                step="0.01"
                                placeholder="Max UV"
                                class="mt-1 block w-full text-center {{ isset($error[$index]['message']) ? 'text-red-600' : '' }}"
                                wire:model.live="locations.{{ $index }}.max_uv"/>
                        <x-input-error for="max_uv" class="mt-2" />
                        @error('locations.' . $index . '.max_uv') <span class="error text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="columns-xs ml-3">
                        <x-input
                                id="max_precipitation"
                                type="number"
                                step="0.01"
                                placeholder="Max Precipitation"
                                class="mt-1 block w-full text-center {{ isset($error[$index]['message']) ? 'text-red-600' : '' }}"
                                wire:model.live="locations.{{ $index }}.max_precipitation" />
                        <x-input-error for="max_precipitation" class="mt-2" />
                        @error('locations.' . $index . '.max_precipitation') <span class="error text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="columns-xs ml-3 mt-1 text-center">
                        <button type="button" wire:click="removeLocation({{ $index }})" class="bg-red-500 text-white px-3 py-3 rounded">
                            ❌
                        </button>
                    </div>

                    <div class="columns-xs ml-3 mt-4 text-center">
                        @if(isset($error[$index]))
                            <span class="error py-3 text-red-600">{{ $error[$index]['message'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="w-60 text-red-600 font-medium">
                <x-button
                        type="button"
                        wire:click="addLocation"
                        class="bg-blue-500 text-white px-3 py-1 rounded mt-2"
                        :disabled="!$canAdd">
                    Add
                </x-button>

                @if(!$canAdd)
                    <span>Limit for add location</span>
                @endif
            </div>
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button>
            {{ __('Save') }}
        </x-button>

    </x-slot>
</x-location-section>
