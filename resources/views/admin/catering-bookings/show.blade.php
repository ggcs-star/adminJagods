@extends('admin.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Catering Booking #{{ $booking->id }}</p>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Booking Details</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Review customer, event, package and item pricing details.
                </p>
            </div>

            <a
                href="{{ route('admin.catering-bookings.index') }}"
                class="inline-flex items-center justify-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-semibold"
            >
                &larr; Back to Bookings
            </a>
        </div>

        {{-- Booking Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Booking ID</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">#{{ $booking->id }}</p>
                <p class="text-xs text-gray-400 mt-2">
                    Created {{ $booking->created_at?->format('d M Y, h:i A') ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Guest Count</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">
                    {{ number_format($booking->guest_count) }}
                </p>
                <p class="text-xs text-gray-400 mt-2">Guests</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Subtotal</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">
                    ₹{{ number_format((float) $booking->sub_total, 2) }}
                </p>
            </div>

            <div class="bg-indigo-600 rounded-xl p-5 shadow-sm text-white">
                <p class="text-sm text-indigo-100">Total Amount</p>
                <p class="text-2xl font-bold mt-2">
                    ₹{{ number_format((float) $booking->total_amount, 2) }}
                </p>
                <p class="text-xs text-indigo-100 mt-2">
                    Status: {{ $booking->status }}
                </p>
            </div>
        </div>

        {{-- Customer + Event --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Customer Information --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Customer Information</h2>
                </div>

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-sm text-gray-500">Customer Name</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->customer_name ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Customer Mobile</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->customer_mobile ?: '—' }}
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Customer Email</p>
                        <p class="font-medium text-gray-900 mt-1 break-all">
                            {{ $booking->customer_email ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">User ID</p>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $booking->user_id ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Address ID</p>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $booking->address_id ?: '—' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Event Information --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Event Information</h2>
                </div>

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-sm text-gray-500">Event Type</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->event_type ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Event Date</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->event_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Event Time</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->event_time ? \Illuminate\Support\Carbon::parse($booking->event_time)->format('h:i A') : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Module ID</p>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $booking->module_id ?? '—' }}
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Special Instructions</p>
                        <p class="text-gray-700 mt-1 whitespace-pre-line break-words">
                            {{ $booking->special_instructions ?: 'No special instructions provided.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Address --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Event Address</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="text-gray-900 font-medium mt-1 whitespace-pre-line">
                        {{ $eventAddress['address'] ?? 'Address not available' }}
                    </p>

                    @if (!empty($eventAddress['apartment']))
                        <p class="text-sm text-gray-600 mt-2">
                            Apartment / Unit: {{ $eventAddress['apartment'] }}
                        </p>
                    @endif

                    @if (!empty($eventAddress['pincode']))
                        <p class="text-sm text-gray-600 mt-1">
                            Pincode: {{ $eventAddress['pincode'] }}
                        </p>
                    @endif

                    @if ($booking->address)
                        @if ($booking->address->landmark)
                            <p class="text-sm text-gray-600 mt-2">
                                Landmark: {{ $booking->address->landmark }}
                            </p>
                        @endif

                        @php
                            $cityLine = collect([
                                $booking->address->city ?? null,
                                $booking->address->state ?? null,
                                $booking->address->country ?? null,
                            ])->filter()->implode(', ');
                        @endphp

                        @if ($cityLine)
                            <p class="text-sm text-gray-500 mt-2">{{ $cityLine }}</p>
                        @endif
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-2">Coordinates</p>

                    <p class="text-sm text-gray-800">
                        Latitude: {{ $booking->event_lat ?? '—' }}
                    </p>
                    <p class="text-sm text-gray-800 mt-1">
                        Longitude: {{ $booking->event_long ?? '—' }}
                    </p>

                    @if ($booking->event_lat !== null && $booking->event_long !== null)
                        <a
                            href="https://www.google.com/maps?q={{ $booking->event_lat }},{{ $booking->event_long }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex mt-3 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-2 text-sm font-semibold"
                        >
                            View on Map
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Package Snapshot --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Catering Package</h2>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                    <div>
                        <p class="text-sm text-gray-500">Package Name</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $booking->package_name ?: $booking->package?->name ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Package ID</p>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $booking->catering_package_id ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Package Price</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            ₹{{ number_format((float) $booking->package_price, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Price Type</p>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ str($booking->package_price_type ?: '—')->replace('_', ' ')->title() }}
                        </p>
                    </div>
                </div>

                @if ($booking->package)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <a
                            href="{{ route('admin.catering-packages.show', $booking->package->id) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold"
                        >
                            View Current Package Details &rarr;
                        </a>
                        <p class="text-xs text-gray-400 mt-1">
                            The booking values above are the saved booking snapshots.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sections & Items --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Booked Sections & Menu Items
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Items and prices saved with this booking.
                    </p>
                </div>

                <span class="rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 px-3 py-1.5 text-sm font-semibold">
                    {{ $booking->sections->count() }} Sections
                </span>
            </div>

            <div class="p-5 space-y-6">
                @forelse ($booking->sections as $sectionIndex => $section)
                    <div class="rounded-xl border border-gray-200 overflow-hidden">

                        {{-- Section Header --}}
                        <div class="p-4 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div>
                                <p class="text-xs uppercase font-bold text-gray-500">
                                    Section {{ $sectionIndex + 1 }}
                                </p>
                                <h3 class="text-base font-bold text-gray-900 mt-1">
                                    {{ $section->name }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    Booking Section ID: #{{ $section->id }}
                                    · Package Section ID: {{ $section->catering_package_section_id }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-indigo-100 text-indigo-700 px-3 py-1 text-xs font-semibold">
                                    {{ str($section->selection_type)->replace('_', ' ')->title() }}
                                </span>
                                <span class="rounded-full bg-gray-100 text-gray-700 px-3 py-1 text-xs font-semibold">
                                    Min: {{ $section->min_selections }}
                                </span>
                                <span class="rounded-full bg-gray-100 text-gray-700 px-3 py-1 text-xs font-semibold">
                                    Max: {{ $section->max_selections }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            @if ($section->description)
                                <p class="text-sm text-gray-600 whitespace-pre-line mb-4">
                                    {{ $section->description }}
                                </p>
                            @endif

                            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                <table class="w-full min-w-[1000px] text-sm text-left text-gray-600">
                                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">Menu Item</th>
                                            <th class="px-4 py-3 font-semibold">Unit Price</th>
                                            <th class="px-4 py-3 font-semibold">Discount</th>
                                            <th class="px-4 py-3 font-semibold">Extra</th>
                                            <th class="px-4 py-3 font-semibold">Final Unit Price</th>
                                            <th class="px-4 py-3 font-semibold">Qty</th>
                                            <th class="px-4 py-3 font-semibold">Item Total</th>
                                            <th class="px-4 py-3 font-semibold">Selection</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($section->items as $item)
                                            @php
                                                $itemName = $item->menu_item_name
                                                    ?: $item->menuItem?->name
                                                    ?: 'Menu Item #' . $item->menu_item_id;

                                                $itemDescription = $item->menu_item_description
                                                    ?: $item->menuItem?->description;

                                                $unitPrice = (float) $item->unit_price;
                                                $discountPrice = (float) $item->discount_price;
                                                $extraPrice = (float) $item->extra_price;
                                                $finalUnitPrice = (float) $item->final_unit_price;
                                            @endphp

                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4">
                                                    <div class="flex items-start gap-3 min-w-[220px]">
                                                        @if ($item->menuItem && !empty($item->menuItem->image))
                                                            <img
                                                                src="{{ $item->menuItem->image }}"
                                                                alt="{{ $itemName }}"
                                                                loading="lazy"
                                                                class="w-12 h-12 rounded-lg object-cover border border-gray-200"
                                                            >
                                                        @endif

                                                        <div>
                                                            <p class="font-semibold text-gray-900">
                                                                {{ $itemName }}
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Item ID: {{ $item->menu_item_id ?? '—' }}
                                                            </p>

                                                            @if ($itemDescription)
                                                                <p class="text-xs text-gray-500 mt-1 max-w-xs">
                                                                    {{ \Illuminate\Support\Str::limit($itemDescription, 100) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    ₹{{ number_format($unitPrice, 2) }}
                                                </td>

                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    @if ($discountPrice > 0)
                                                        <span class="font-semibold text-red-600">
                                                            −₹{{ number_format($discountPrice, 2) }}
                                                        </span>
                                                        <p class="text-xs text-gray-400 mt-1">
                                                            {{ $unitPrice > 0 ? round(($discountPrice / $unitPrice) * 100) : 0 }}% off
                                                        </p>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    @if ($extraPrice > 0)
                                                        <span class="font-semibold text-orange-600">
                                                            +₹{{ number_format($extraPrice, 2) }}
                                                        </span>
                                                    @else
                                                        ₹0.00
                                                    @endif
                                                </td>

                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="font-bold text-gray-900">
                                                        ₹{{ number_format($finalUnitPrice, 2) }}
                                                    </span>
                                                </td>

                                                <td class="px-4 py-4">
                                                    {{ number_format((int) $item->quantity) }}
                                                </td>

                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="font-bold text-gray-900">
                                                        ₹{{ number_format((float) $item->item_total, 2) }}
                                                    </span>
                                                </td>

                                                <td class="px-4 py-4">
                                                    @if ($item->is_selected)
                                                        <span class="inline-flex rounded-full bg-green-100 text-green-700 px-2.5 py-1 text-xs font-semibold">
                                                            Selected
                                                        </span>
                                                    @else
                                                        <span class="inline-flex rounded-full bg-gray-100 text-gray-600 px-2.5 py-1 text-xs font-semibold">
                                                            Not Selected
                                                        </span>
                                                    @endif

                                                    @if ($item->is_default)
                                                        <p class="mt-1">
                                                            <span class="text-xs text-blue-600 font-semibold">
                                                                Default item
                                                            </span>
                                                        </p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                                    No menu items recorded for this section.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <h3 class="font-semibold text-gray-700">
                            No booking sections available
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            No section snapshots were recorded for this booking.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pricing Summary --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-5">
                Booking Pricing Summary
            </h2>

            <div class="max-w-md ml-auto space-y-4">
                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-gray-500">Package Price Snapshot</span>
                    <span class="font-medium text-gray-900">
                        ₹{{ number_format((float) $booking->package_price, 2) }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-gray-500">Guest Count</span>
                    <span class="font-medium text-gray-900">
                        {{ number_format($booking->guest_count) }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium text-gray-900">
                        ₹{{ number_format((float) $booking->sub_total, 2) }}
                    </span>
                </div>

                <div class="border-t border-gray-200 pt-4 flex justify-between gap-4">
                    <span class="font-bold text-gray-900">Total Amount</span>
                    <span class="text-xl font-bold text-indigo-700">
                        ₹{{ number_format((float) $booking->total_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Record Metadata --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Record Information
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <p class="text-sm text-gray-500">Booking ID</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $booking->id }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="font-medium text-gray-900 mt-1">
                        {{ $booking->created_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="font-medium text-gray-900 mt-1">
                        {{ $booking->updated_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Deleted At</p>
                    <p class="font-medium text-gray-900 mt-1">
                        {{ $booking->deleted_at?->format('d M Y, h:i A') ?? 'Not deleted' }}
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection