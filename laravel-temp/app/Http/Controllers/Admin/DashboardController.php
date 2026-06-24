<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Trip;
use App\Models\Culinary;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            ['label' => 'Hidden Gems',  'value' => Destination::count(), 'icon' => 's-compass',  'color' => 'accent'],
            ['label' => 'Open Trip',    'value' => Trip::count(),        'icon' => 's-map',      'color' => 'coral'],
            ['label' => 'Kuliner',      'value' => Culinary::count(),    'icon' => 's-utensils', 'color' => 'gold'],
            ['label' => 'Pengguna',     'value' => User::count(),        'icon' => 's-users',    'color' => 'accent'],
        ];

        $recentDestinations = Destination::latest()->take(3)->get();
        $recentTrips        = Trip::latest()->take(3)->get();
        $recentCulinaries   = Culinary::latest()->take(3)->get();

        return view('admin.dashboard', compact('stats', 'recentDestinations', 'recentTrips', 'recentCulinaries'));
    }
}
