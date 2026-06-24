<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->paginate(10);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'description'  => 'required|string',
            'label'        => 'required|in:Hidden Gem,Populer,Surfing',
            'category'     => 'required|in:Pantai,Teluk,Air Terjun,Danau,Pulau,Gunung,Hutan',
            'distance_km'  => 'nullable|string|max:100',
            'travel_time'  => 'nullable|string|max:100',
            'entrance_fee' => 'nullable|string|max:100',
            'best_time'    => 'nullable|string|max:100',
            'rating'       => 'nullable|numeric|min:0|max:5',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('destinations', 'public');
        $validated['image_path'] = $path;
        $validated['rating']     = $validated['rating'] ?? 0;
        $validated['likes_count'] = 0;
        unset($validated['image']);

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'description'  => 'required|string',
            'label'        => 'required|in:Hidden Gem,Populer,Surfing',
            'category'     => 'required|in:Pantai,Teluk,Air Terjun,Danau,Pulau,Gunung,Hutan',
            'distance_km'  => 'nullable|string|max:100',
            'travel_time'  => 'nullable|string|max:100',
            'entrance_fee' => 'nullable|string|max:100',
            'best_time'    => 'nullable|string|max:100',
            'rating'       => 'nullable|numeric|min:0|max:5',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if stored in storage
            if ($destination->image_path && !str_starts_with($destination->image_path, 'images/')) {
                Storage::disk('public')->delete($destination->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('destinations', 'public');
        }
        unset($validated['image']);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "' . $destination->name . '" berhasil diperbarui!');
    }

    public function destroy(Destination $destination)
    {
        $name = $destination->name;
        if ($destination->image_path && !str_starts_with($destination->image_path, 'images/')) {
            Storage::disk('public')->delete($destination->image_path);
        }
        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "' . $name . '" berhasil dihapus.');
    }
}
