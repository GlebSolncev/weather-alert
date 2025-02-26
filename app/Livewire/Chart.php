<?php

namespace App\Livewire;

use App\Http\Resources\ChartResource;
use App\Models\User;
use App\Services\Location\LocationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Chart extends Component
{
    private const LIMIT_CHART_DATA = 48;

    public array $charts = [];

    public bool $loading = false;

    public function mount(LocationService $service): void
    {
        $this->loading = true;
        /** @var User $user */
        $user = Auth::user();

        try {
            $this->charts = ChartResource::collection(
                $service->getWeatherConditions($user, self::LIMIT_CHART_DATA)
            )->toArray(request());

            $this->dispatch('initChartData', [
                'charts' => $this->charts,
            ]);
        } finally {
            $this->loading = false;
        }
    }

    public function render(): View
    {
        return view('livewire.chart');
    }
}
