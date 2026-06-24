<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Destination;
use App\Models\Trip;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama Tapak Lampung.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $destinations = \App\Models\Destination::latest()->get();
        $culinaries   = \App\Models\Culinary::latest()->get();
        $trips        = Trip::with('tags')->latest()->get();
        return view('home', compact('destinations', 'culinaries', 'trips'));
    }

    /**
     * Menampilkan halaman detail destinasi (Hidden Gems).
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDestination($id)
    {
        $destination = Destination::findOrFail($id);
        
        // Related destinations for recommendations
        $related = Destination::where('id', '!=', $id)
            ->where('category', $destination->category)
            ->take(3)
            ->get();
            
        if ($related->isEmpty()) {
            $related = Destination::where('id', '!=', $id)
                ->take(3)
                ->get();
        }

        return view('destination.show', compact('destination', 'related'));
    }

    /**
     * Menangani fungsi pencarian global (Destinasi, Trip, Kuliner).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');

        // Pencarian Destinasi
        $destinations = Destination::query();
        if ($query) {
            $destinations->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }
        if ($category && $category !== 'Semua') {
            if ($category === 'Pantai') {
                $destinations->whereIn('category', ['Pantai', 'Teluk', 'Pantai & Laut']);
            } else {
                $destinations->where('category', $category);
            }
        }
        $destinations = $destinations->get();

        // Pencarian Kuliner
        $culinaries = collect();
        if (!$category || $category === 'Semua') {
            $culQuery = \App\Models\Culinary::query();
            if ($query) {
                $culQuery->where('name', 'LIKE', "%{$query}%")
                         ->orWhere('description', 'LIKE', "%{$query}%")
                         ->orWhere('category', 'LIKE', "%{$query}%");
            }
            $culinaries = $culQuery->get();
        }

        // Pencarian Trip
        $trips = collect();
        if (!$category || $category === 'Semua') {
            $tripQuery = Trip::with('tags');
            if ($query) {
                $tripQuery->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('organizer_name', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
            }
            $trips = $tripQuery->get();
        }

        return view('search.index', compact('destinations', 'culinaries', 'trips', 'query', 'category'));
    }
}
