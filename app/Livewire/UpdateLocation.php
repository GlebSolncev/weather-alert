<?php

namespace App\Livewire;

use App\Http\Resources\LocationResource;
use App\Services\Location\UpdateLocation\DTO\UpdateLocationDTO;
use App\Services\Location\UpdateLocation\UpdateLocationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class UpdateLocation extends Component
{
    use InteractsWithBanner;

    private const MAX_LOCATIONS = 3;

    public array $locations = [];

    public bool $canAdd = true;

    public function getRules(): array
    {
        return [
            'locations' => ['required', 'array', 'min:1', 'max:'.self::MAX_LOCATIONS],
            'locations.*.city' => 'required|string|min:3',
            'locations.*.country' => 'required|string|min:3',
            'locations.*.max_uv' => 'required|numeric|min:0|max:100',
            'locations.*.max_precipitation' => 'required|numeric|min:0|max:100',
        ];
    }

    public function mount(): void
    {
        $this->refreshLocations();
    }

    public function render(): View
    {
        return view('livewire.update-location');
    }

    public function updateLocation(UpdateLocationService $service): void
    {
        try {
            $data = $this->validate();
            $data['user'] = Auth::user();
            $this->locations = $service->process(new UpdateLocationDTO($data));
            $this->banner('Locations updated successfully!');
            $this->refreshLocations();
        } catch (\Exception $e) {
            Log::error('Location update failed', ['error' => $e->getMessage()]);
            $this->dangerBanner('Failed to update locations. Please try again.');
        }
    }

    public function removeLocation(int $index): void
    {
        if ($this->locations[$index] ?? null) {
            unset($this->locations[$index]);
            $this->canAdd = true;
        }
    }

    public function addLocation(): void
    {
        if (count($this->locations) >= self::MAX_LOCATIONS) {
            $this->addError('locations', 'Maximum locations limit reached');

            return;
        }

        $this->locations[] = $this->getEmptyLocationData();
        $this->canAdd = count($this->locations) < self::MAX_LOCATIONS;
    }

    private function getEmptyLocationData(): array
    {
        return [
            'country' => '',
            'city' => '',
            'max_uv' => '',
            'max_precipitation' => '',
        ];
    }

    private function refreshLocations(): void
    {
        $this->locations = LocationResource::collection(
            Auth::user()->locations
        )->toArray(request());

        $this->canAdd = count($this->locations) < self::MAX_LOCATIONS;
    }
}
