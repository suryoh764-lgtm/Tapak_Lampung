<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with('tags')->latest()->paginate(10);
        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        return view('admin.trips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'organizer_name'   => 'required|string|max:255',
            'organizer_avatar' => 'required|string|max:10',
            'schedule_date'    => 'required|date',
            'duration'         => 'required|string|max:100',
            'max_quota'        => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'rating'           => 'nullable|numeric|min:0|max:5',
            'image'            => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tags'             => 'nullable|string',
        ]);

        $path = $request->file('image')->store('trips', 'public');
        $validated['image_path']    = $path;
        $validated['current_quota'] = 0;
        $validated['rating']        = $validated['rating'] ?? 0;
        $validated['reviews_count'] = 0;
        $tagsRaw = $validated['tags'] ?? '';
        unset($validated['image'], $validated['tags']);

        $trip = Trip::create($validated);

        // Save tags
        if ($tagsRaw) {
            foreach (array_filter(array_map('trim', explode(',', $tagsRaw))) as $tag) {
                TripTag::create(['trip_id' => $trip->id, 'tag' => $tag]);
            }
        }

        return redirect()->route('admin.trips.index')
            ->with('success', 'Open Trip "' . $trip->name . '" berhasil ditambahkan!');
    }

    public function edit(Trip $trip)
    {
        $trip->load('tags');
        return view('admin.trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'organizer_name'   => 'required|string|max:255',
            'organizer_avatar' => 'required|string|max:10',
            'schedule_date'    => 'required|date',
            'duration'         => 'required|string|max:100',
            'max_quota'        => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'rating'           => 'nullable|numeric|min:0|max:5',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tags'             => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($trip->image_path && !str_starts_with($trip->image_path, 'images/')) {
                Storage::disk('public')->delete($trip->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('trips', 'public');
        }
        $tagsRaw = $validated['tags'] ?? '';
        unset($validated['image'], $validated['tags']);

        $trip->update($validated);

        // Update tags
        $trip->tags()->delete();
        if ($tagsRaw) {
            foreach (array_filter(array_map('trim', explode(',', $tagsRaw))) as $tag) {
                TripTag::create(['trip_id' => $trip->id, 'tag' => $tag]);
            }
        }

        return redirect()->route('admin.trips.index')
            ->with('success', 'Open Trip "' . $trip->name . '" berhasil diperbarui!');
    }

    public function destroy(Trip $trip)
    {
        $name = $trip->name;
        if ($trip->image_path && !str_starts_with($trip->image_path, 'images/')) {
            Storage::disk('public')->delete($trip->image_path);
        }
        $trip->tags()->delete();
        $trip->delete();

        return redirect()->route('admin.trips.index')
            ->with('success', 'Open Trip "' . $name . '" berhasil dihapus.');
    }
}
