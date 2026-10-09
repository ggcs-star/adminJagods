<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringBooking;
use Illuminate\Http\Request;

class CateringBookingController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer'],
            'event_date' => ['nullable', 'date'],
        ]);

        $query = CateringBooking::query()
            ->with([
                'user',
                'package',
            ])
            ->withCount('sections');

        // Search customer, package, mobile, email, event type or booking ID.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('package_name', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        // Filter by actual stored status value.
        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        // Filter bookings by event date.
        if ($request->filled('event_date')) {
            $query->whereDate('event_date', $request->event_date);
        }

        $bookings = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Use actual statuses in the database; no assumptions about their meaning.
        $statuses = CateringBooking::query()
            ->whereNotNull('status')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        return view(
            'admin.catering-bookings.index',
            compact('bookings', 'statuses')
        );
    }

    /**
     * Display complete catering booking details.
     */
    public function show($id)
    {
        $booking = CateringBooking::query()
            ->with([
                'user',
                'package',
                'address',
                'sections' => function ($query) {
                    $query->orderBy('sort_order', 'asc');
                },
                'sections.packageSection',
                'sections.items' => function ($query) {
                    $query->orderBy('sort_order', 'asc');
                },
                'sections.items.menuItem',
                'sections.items.packageItem',
            ])
            ->findOrFail($id);

        // Decode the address snapshot saved when the booking was created.
        $rawAddress = $booking->event_address;

        if (is_array($rawAddress)) {
            $eventAddress = $rawAddress;
        } else {
            $decodedAddress = json_decode((string) $rawAddress, true);

            $eventAddress = is_array($decodedAddress)
                ? $decodedAddress
                : ['address' => $rawAddress];
        }

        return view(
            'admin.catering-bookings.show',
            compact('booking', 'eventAddress')
        );
    }
}
