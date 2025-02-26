<?php

use App\Livewire\Chart;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()
        ->set('password', 'secret')
        ->create();
    Auth::attempt([
        'email' => $user->email,
        'password' => 'secret',
    ]);

    $location = Location::factory()->create();
    $user->locations()->attach([$location->id]);

    Livewire::test(Chart::class)
        ->assertStatus(200);
});
