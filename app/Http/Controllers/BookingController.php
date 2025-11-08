<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'booking_date' => 'required|date',
            'booking_type' => 'required|in:full_day,half_day,custom',
            // 'booking_slot' => 'nullable|in:first_half,second_half',
            'booking_slot' => 'required_if:booking_type,half_day|in:first_half,second_half|nullable',
            // 'booking_from' => 'nullable|date_format:H:i',
            // 'booking_to' => 'nullable|date_format:H:i|after:booking_from',
            'booking_from' => 'required_if:booking_type,custom|date_format:H:i|nullable',
            'booking_to'   => 'required_if:booking_type,custom|date_format:H:i|after:booking_from|nullable',
        ],
    [
    'customer_name.required' => 'Customer name is required.',
    'customer_email.required' => 'Customer email is required.',
    'customer_email.email' => 'Please enter a valid email address.',
    'booking_date.required' => 'Please select a booking date.',
    'booking_type.required' => 'Please select a booking type.',
    // 'booking_slot.required' => 'Please select a booking slot.',
    'booking_slot.required_if' => 'Booking slot is required when type is Half Day.',
    'booking_from.required_if' => 'Start time is required for Custom booking.',
    'booking_to.required_if' => 'End time is required for Custom booking.',
    // 'booking_from.required' => 'Start time is required.',
    // 'booking_to.required' => 'End time is required.',
    'booking_to.after' => 'End time must be after start time.',
]);

        $date = $validated['booking_date'];

        // ✅ Overlap restriction logic
        $query = Booking::where('booking_date', $date);

        // Full-day booking check
        if ($validated['booking_type'] === 'full_day') {
            if ($query->exists()) {
                return back()->withErrors(['error' => 'Day already has bookings.']);
            }
        }

        // Half-day booking check
        if ($validated['booking_type'] === 'half_day') {
            if (
                $query->where(function ($q) use ($validated) {
                    $slot = $validated['booking_slot'];
                    $q->where('booking_type', 'full_day')
                      ->orWhere(function ($q2) use ($slot) {
                          $q2->where('booking_type', 'half_day')
                             ->where('booking_slot', $slot);
                      })
                      ->orWhere(function ($q3) use ($slot) {
                          if ($slot === 'first_half') {
                              $q3->where('booking_type', 'custom')
                                 ->whereBetween('booking_from', ['00:00:00', '12:00:00']);
                          } else {
                              $q3->where('booking_type', 'custom')
                                 ->whereBetween('booking_from', ['12:00:00', '23:59:59']);
                          }
                      });
                })->exists()
            ) {
                return back()->withErrors(['error' => 'Booking slot overlaps existing booking.']);
            }
        }

        // Custom booking check
        if ($validated['booking_type'] === 'custom') {
            $from = $validated['booking_from'];
            $to = $validated['booking_to'];

            $conflict = $query->where(function ($q) use ($from, $to) {
                $q->where('booking_type', 'full_day')
                  ->orWhere(function ($q2) use ($from, $to) {
                      $q2->where('booking_type', 'half_day')
                         ->where(function ($q3) use ($from, $to) {
                             // Assume first half = 00:00–12:00, second = 12:00–23:59
                             if ($from < '12:00:00') {
                                 $q3->where('booking_slot', 'first_half');
                             } else {
                                 $q3->where('booking_slot', 'second_half');
                             }
                         });
                  })
                  ->orWhere(function ($q4) use ($from, $to) {
                      $q4->where('booking_type', 'custom')
                         ->where(function ($q5) use ($from, $to) {
                             $q5->whereBetween('booking_from', [$from, $to])
                                ->orWhereBetween('booking_to', [$from, $to])
                                ->orWhere(function ($q6) use ($from, $to) {
                                    $q6->where('booking_from', '<', $from)
                                       ->where('booking_to', '>', $to);
                                });
                         });
                  });
            })->exists();

            if ($conflict) {
                return back()->withErrors(['error' => 'Custom booking overlaps with existing bookings.']);
            }
        }

        // Save booking
        $validated['user_id'] = Auth::id();
        Booking::create($validated);

        return back()->with('success', 'Booking created successfully!');
    }
}
