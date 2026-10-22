@extends('admin.app')

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Catering Bookings</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Manage customer bookings, events, packages and pricing.
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                <span class="text-sm text-gray-500">Total matching bookings</span>
                <p class="text-xl font-bold text-gray-900">
                    {{ number_format($bookings->total()) }}
                </p>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="p-5">
                <form action="{{ route('admin.catering-bookings.index') }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Search --}}
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Search
                        </label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Customer, mobile, package, email or ID..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            <option value="">All Statuses</option>

                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') !== null && request('status') !== '' && (string) request('status') === (string) $status)>
                                    Status {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Event Date --}}
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Event Date
                        </label>
                        <input type="date" name="event_date" id="event_date" value="{{ request('event_date') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    </div>

                    {{-- Buttons --}}
                    <div class="lg:col-span-4 flex flex-wrap items-center gap-3 pt-2">

                        <!-- Search / Filter Button -->
                        <button type="submit" style="background: linear-gradient(135deg, #dc2626, #b91c1c);"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-md shadow-red-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-200 active:translate-y-0">
                            <i class="fas fa-search"></i>
                            <span>Search / Filter</span>
                        </button>

                        <!-- Reset Button -->
                        <a href="{{ route('admin.catering-bookings.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-50 hover:shadow-md active:translate-y-0">
                            <i class="fas fa-undo-alt text-gray-500"></i>
                            <span>Reset</span>
                        </a>

                    </div>

                </form>
            </div>
        </div>

        {{-- Bookings Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 border-b border-gray-200 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Booking</th>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Package</th>
                            <th class="px-5 py-4 font-semibold">Event</th>
                            <th class="px-5 py-4 font-semibold">Guests</th>
                            <th class="px-5 py-4 font-semibold">Amount</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">

                                {{-- Booking ID --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">#{{ $booking->id }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $booking->created_at?->format('d M Y') ?? '—' }}
                                    </p>
                                </td>

                                {{-- Customer --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900">
                                        {{ $booking->customer_name ?: '—' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $booking->customer_mobile ?: 'No mobile' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $booking->customer_email ?: 'No email' }}
                                    </p>
                                </td>

                                {{-- Package --}}
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ $booking->package_name ?: $booking->package?->name ?: 'Package unavailable' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ str($booking->package_price_type ?: $booking->package?->price_type ?: '—')->replace('_', ' ')->title() }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $booking->sections_count }} sections
                                    </p>
                                </td>

                                {{-- Event --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-medium text-gray-900">
                                        {{ $booking->event_type ?: 'Event not specified' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $booking->event_date?->format('d M Y') ?? '—' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $booking->event_time ? \Illuminate\Support\Carbon::parse($booking->event_time)->format('h:i A') : 'Time not set' }}
                                    </p>
                                </td>

                                {{-- Guests --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold border border-blue-100">
                                        {{ number_format($booking->guest_count) }} guests
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-bold text-gray-900">
                                        ₹{{ number_format((float) $booking->total_amount, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Subtotal: ₹{{ number_format((float) $booking->sub_total, 2) }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 text-gray-700 px-3 py-1 text-xs font-semibold">
                                        Status {{ $booking->status }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.catering-bookings.show', $booking->id) }}"
                                        class="inline-flex items-center rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-2 font-semibold text-xs transition">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <p class="text-base font-semibold text-gray-700">
                                        No catering bookings found
                                    </p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Try changing the search terms or filters.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages() || $bookings->total() > 0)
                <div
                    class="border-t border-gray-200 bg-gray-50/70 px-5 py-4
               flex flex-col gap-4
               sm:flex-row sm:items-center sm:justify-between">

                    {{-- Booking Count --}}
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center
                       rounded-xl bg-red-50 text-red-600">
                            <i class="fas fa-calendar-check text-lg"></i>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                Booking Records
                            </p>

                            <p class="text-xs text-gray-500">
                                Showing
                                <span class="font-semibold text-gray-700">
                                    {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }}
                                </span>
                                of
                                <span class="font-semibold text-gray-700">
                                    {{ $bookings->total() }}
                                </span>
                                bookings
                            </p>
                        </div>
                    </div>

                    {{-- Pagination Links --}}
                    {{-- Pagination --}}
                    @if ($bookings->hasPages())
                        <div class="mt-4 border-t border-gray-200 bg-white px-4 py-5 sm:px-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                {{-- Left: Page Information --}}
                                <div class="text-sm text-gray-500">
                                    Page
                                    <span class="font-semibold text-gray-900">
                                        {{ $bookings->currentPage() }}
                                    </span>
                                    of
                                    <span class="font-semibold text-gray-900">
                                        {{ $bookings->lastPage() }}
                                    </span>

                                    <span class="mx-2 text-gray-300">|</span>

                                    Total
                                    <span class="font-semibold text-gray-900">
                                        {{ $bookings->total() }}
                                    </span>
                                    bookings
                                </div>

                                {{-- Right: Pagination --}}
                                <div class="flex items-center justify-between gap-2 sm:justify-end">

                                    {{-- Previous --}}
                                    @if ($bookings->onFirstPage())
                                        <span
                                            class="inline-flex cursor-not-allowed items-center gap-2 rounded-lg
                               border border-gray-200 bg-gray-50 px-3 py-2
                               text-sm font-medium text-gray-400">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                            <span>Previous</span>
                                        </span>
                                    @else
                                        <a href="{{ $bookings->previousPageUrl() }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200
                               bg-white px-3 py-2 text-sm font-medium text-gray-700
                               shadow-sm transition hover:border-red-300 hover:bg-red-50
                               hover:text-red-600">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                            <span>Previous</span>
                                        </a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    <div class="flex items-center gap-1">
                                        @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                                            @if ($page == $bookings->currentPage())
                                                <span aria-current="page"
                                                    class="inline-flex h-9 min-w-9 items-center justify-center
                                       rounded-lg bg-red-600 px-3 text-sm font-semibold
                                       text-white shadow-sm shadow-red-200">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $url }}"
                                                    class="inline-flex h-9 min-w-9 items-center justify-center
                                       rounded-lg border border-gray-200 bg-white px-3
                                       text-sm font-medium text-gray-600 transition
                                       hover:border-red-300 hover:bg-red-50 hover:text-red-600">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Next --}}
                                    @if ($bookings->hasMorePages())
                                        <a href="{{ $bookings->nextPageUrl() }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200
                               bg-white px-3 py-2 text-sm font-medium text-gray-700
                               shadow-sm transition hover:border-red-300 hover:bg-red-50
                               hover:text-red-600">
                                            <span>Next</span>
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    @else
                                        <span
                                            class="inline-flex cursor-not-allowed items-center gap-2 rounded-lg
                               border border-gray-200 bg-gray-50 px-3 py-2
                               text-sm font-medium text-gray-400">
                                            <span>Next</span>
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            @endif
        </div>
    </div>
@endsection
