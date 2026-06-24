<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('trip')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('booking_code', 'like', "%$s%");
            });
        }

        $bookings = $query->paginate(15);

        $stats = [
            'total'     => Booking::count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'paid'      => Booking::where('status', 'paid')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load('trip');
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,paid,confirmed,cancelled']);
        
        $data = ['status' => $request->status];
        if ($request->status === 'confirmed') {
            $data['confirmed_at'] = Carbon::now();
        }

        $booking->update($data);
        return back()->with('success', 'Status booking #' . $booking->booking_code . ' berhasil diperbarui!');
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'paid') {
            return back()->with('error', 'Hanya pemesanan berstatus "Dibayar" yang bisa dikonfirmasi.');
        }

        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => Carbon::now(),
        ]);

        return back()->with('success', '✅ Pemesanan ' . $booking->booking_code . ' berhasil dikonfirmasi!');
    }
}
