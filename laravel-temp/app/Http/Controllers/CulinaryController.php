<?php

namespace App\Http\Controllers;

use App\Models\Culinary;
use App\Models\Restaurant;
use App\Models\Booking;
use Illuminate\Http\Request;

class CulinaryController extends Controller
{
    public function show($id)
    {
        $culinary    = Culinary::findOrFail($id);
        $restaurants = Restaurant::where('culinary_id', $id)->orderByDesc('rating')->get();
        $others      = Culinary::where('id', '!=', $id)->take(3)->get();
        return view('culinary.show', compact('culinary', 'restaurants', 'others'));
    }

    public function restaurant($id)
    {
        $restaurant = Restaurant::with('culinary')->findOrFail($id);
        $similar    = Restaurant::where('culinary_id', $restaurant->culinary_id)
                        ->where('id', '!=', $id)->take(3)->get();
        return view('culinary.restaurant', compact('restaurant', 'similar'));
    }

    public function bookForm($id)
    {
        $restaurant = Restaurant::with('culinary')->findOrFail($id);
        return view('culinary.book', compact('restaurant'));
    }

    public function bookStore(Request $request, $id)
    {
        $restaurant = Restaurant::with('culinary')->findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'date'  => 'required|date',
            'pax'   => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $bookingCode = 'KUL-' . strtoupper(substr(md5(uniqid()), 0, 8));

        // ✅ Simpan ke database
        $booking = Booking::create([
            'booking_code'       => $bookingCode,
            'type'               => 'kuliner',
            'restaurant_id'      => $restaurant->id,
            'name'               => $validated['name'],
            'phone'              => $validated['phone'],
            'email'              => $validated['email'],
            'participants_count' => $validated['pax'],
            'total_price'        => 0,
            'booking_date'       => $validated['date'],
            'notes'              => $validated['notes'] ?? null,
            'status'             => 'pending',
        ]);

        session([
            'booking_success' => [
                'type'         => 'Reservasi Kuliner',
                'item_name'    => $restaurant->name . ' — ' . $restaurant->culinary->name,
                'name'         => $validated['name'],
                'phone'        => $validated['phone'],
                'email'        => $validated['email'],
                'participants' => $validated['pax'],
                'total_price'  => 0,
                'schedule'     => $validated['date'],
                'duration'     => 'Makan di tempat',
                'organizer'    => $restaurant->name,
                'location'     => $restaurant->address,
                'booking_code' => $bookingCode,
                'booking_id'   => $booking->id,
            ]
        ]);

        return redirect()->route('culinary.invoice');
    }

    public function invoice()
    {
        $booking = session('booking_success');
        if (!$booking) {
            return redirect()->route('home');
        }
        session(['invoice_back_url' => url()->previous()]);
        return view('trip.invoice', compact('booking'));
    }
}
