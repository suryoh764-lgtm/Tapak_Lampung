<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CulinaryController extends Controller
{
    public function index()
    {
        $culinaries = Culinary::latest()->paginate(10);
        return view('admin.culinaries.index', compact('culinaries'));
    }

    public function create()
    {
        return view('admin.culinaries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Makanan,Minuman,Camilan',
            'spice_level'  => 'required|integer|min:0|max:5',
            'outlet_count' => 'nullable|integer|min:0',
            'outlet_type'  => 'required|in:warung,kafe,restoran',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('culinaries', 'public');
        $validated['image_path'] = $path;
        $validated['outlet_count'] = $validated['outlet_count'] ?? 0;
        unset($validated['image']);

        Culinary::create($validated);

        return redirect()->route('admin.culinaries.index')
            ->with('success', 'Kuliner "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    public function edit(Culinary $culinary)
    {
        return view('admin.culinaries.edit', compact('culinary'));
    }

    public function update(Request $request, Culinary $culinary)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Makanan,Minuman,Camilan',
            'spice_level'  => 'required|integer|min:0|max:5',
            'outlet_count' => 'nullable|integer|min:0',
            'outlet_type'  => 'required|in:warung,kafe,restoran',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($culinary->image_path && !str_starts_with($culinary->image_path, 'images/')) {
                Storage::disk('public')->delete($culinary->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('culinaries', 'public');
        }
        unset($validated['image']);

        $culinary->update($validated);

        return redirect()->route('admin.culinaries.index')
            ->with('success', 'Kuliner "' . $culinary->name . '" berhasil diperbarui!');
    }

    public function destroy(Culinary $culinary)
    {
        $name = $culinary->name;
        if ($culinary->image_path && !str_starts_with($culinary->image_path, 'images/')) {
            Storage::disk('public')->delete($culinary->image_path);
        }
        $culinary->delete();

        return redirect()->route('admin.culinaries.index')
            ->with('success', 'Kuliner "' . $name . '" berhasil dihapus.');
    }
}
