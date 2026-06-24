<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Booking;

class TripController extends Controller
{
    public function book($id)
    {
        $trip = Trip::with('tags')->findOrFail($id);
        return view('trip.book', compact('trip'));
    }

    public function store(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'participants' => 'required|integer|min:1|max:' . ($trip->max_quota - $trip->current_quota),
            'notes'        => 'nullable|string|max:500',
        ]);

        $total       = $trip->price * $validated['participants'];
        $bookingCode = 'TL-' . strtoupper(substr(md5(uniqid()), 0, 8));

        // ✅ Simpan ke database
        $booking = Booking::create([
            'booking_code'      => $bookingCode,
            'type'              => 'trip',
            'trip_id'           => $trip->id,
            'name'              => $validated['name'],
            'phone'             => $validated['phone'],
            'email'             => $validated['email'],
            'participants_count'=> $validated['participants'],
            'total_price'       => $total,
            'booking_date'      => $trip->schedule_date,
            'notes'             => $validated['notes'] ?? null,
            'status'            => 'pending',
        ]);

        // Simpan ke session untuk halaman sukses & invoice
        session([
            'booking_success' => [
                'type'         => 'Open Trip',
                'trip_name'    => $trip->name,
                'item_name'    => $trip->name,
                'name'         => $validated['name'],
                'phone'        => $validated['phone'],
                'email'        => $validated['email'],
                'participants' => $validated['participants'],
                'total_price'  => $total,
                'schedule'     => $trip->schedule_date,
                'duration'     => $trip->duration,
                'organizer'    => $trip->organizer_name,
                'booking_code' => $bookingCode,
                'booking_id'   => $booking->id,
            ]
        ]);

        return redirect()->route('trips.success');
    }

    public function success()
    {
        $booking = session('booking_success');
        if (!$booking) {
            return redirect()->route('home');
        }
        return view('trip.success', compact('booking'));
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
