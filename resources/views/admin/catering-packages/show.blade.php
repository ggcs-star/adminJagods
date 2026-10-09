@extends('admin.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Catering Package Details
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    View complete package information, sections and menu items.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.catering-packages.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium">
                    <span>&larr;</span>
                    Back to Packages
                </a>

                <a href="{{ route('admin.catering-packages.edit', $package->id) }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium">
                    Edit Package
                </a>
            </div>
        </div>


        {{-- Flash Message --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif


        {{-- Package Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            {{-- Cover Image --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Package Cover</h2>
                </div>

                @if ($coverImage)
                    <img src="{{ $coverImage->getUrl() }}" alt="{{ $package->name }}" class="w-full h-64 object-cover">
                @else
                    <div class="h-64 flex flex-col items-center justify-center bg-gray-50 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mb-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 16l5-5 4 4 5-7 4 5M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <span class="text-sm">No cover image available</span>
                    </div>
                @endif

                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $package->name }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Package ID: #{{ $package->id }}
                    </p>

                    <div class="mt-4">
                        @if ((int) $package->status === 1)
                            <span
                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Active
                            </span>
                        @else
                            <span
                                class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>


            {{-- Pricing Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h2 class="font-semibold text-gray-800 border-b border-gray-200 pb-4">
                    Pricing Information
                </h2>

                <div class="py-5 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Package Price</p>

                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        ₹{{ number_format((float) $package->price, 2) }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1 capitalize">
                        {{ str_replace('_', ' ', $package->price_type) }}
                    </p>
                </div>

                <div class="py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Minimum Guests</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">
                        {{ $package->min_guests }}
                    </p>
                </div>

                <div class="py-4">
                    <p class="text-sm text-gray-500">Maximum Guests</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">
                        {{ $package->max_guests ?: 'No limit specified' }}
                    </p>
                </div>
            </div>


            {{-- General Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h2 class="font-semibold text-gray-800 border-b border-gray-200 pb-4">
                    General Information
                </h2>

                <div class="py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Category</p>
                    <p class="font-medium text-gray-800 mt-1">
                        {{ $package->category?->name ?? 'No category assigned' }}
                    </p>
                </div>

                <div class="py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Slug</p>
                    <p class="font-medium text-gray-800 mt-1 break-all">
                        {{ $package->slug ?: '—' }}
                    </p>
                </div>

                <div class="py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Lead Time</p>
                    <p class="font-medium text-gray-800 mt-1">
                        {{ $package->lead_time_hours }} hours
                    </p>
                </div>

                <div class="py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Sort Order</p>
                    <p class="font-medium text-gray-800 mt-1">
                        {{ $package->sort_order }}
                    </p>
                </div>

                <div class="pt-4">
                    <p class="text-sm text-gray-500">Module ID</p>
                    <p class="font-medium text-gray-800 mt-1">
                        {{ $package->module_id }}
                    </p>
                </div>
            </div>

        </div>


        {{-- Description --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                Package Description
            </h2>

            @if (filled($package->description))
                <div class="text-sm text-gray-600 leading-7 whitespace-pre-line">{{ $package->description }}</div>
            @else
                <p class="text-sm text-gray-400">
                    No description has been added.
                </p>
            @endif
        </div>


        {{-- Gallery Images --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Gallery Images
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $galleryImages->count() }} gallery image(s)
                    </p>
                </div>
            </div>

            @if ($galleryImages->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($galleryImages as $image)
                        <a href="{{ $image->getUrl() }}" target="_blank" rel="noopener noreferrer"
                            class="block rounded-lg overflow-hidden border border-gray-200 bg-gray-50">

                            <img src="{{ $image->getUrl() }}" alt="{{ $package->name }} gallery image" loading="lazy"
                                class="w-full h-44 object-cover hover:scale-105 transition-transform duration-200">
                        </a>
                    @endforeach
                </div>
            @else
                <div
                    class="rounded-lg bg-gray-50 border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
                    No gallery images available.
                </div>
            @endif
        </div>


        {{-- Sections --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">

            <div class="p-5 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Package Sections
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        All sections and their configured menu items.
                    </p>
                </div>

                <span class="bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-3 py-1.5 text-sm font-semibold">
                    {{ $package->sections->count() }} Sections
                </span>
            </div>


            <div class="p-5 space-y-6">

                @forelse ($package->sections as $sectionIndex => $section)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">

                        {{-- Section Header --}}
                        <div class="p-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-bold text-gray-500">
                                            SECTION {{ $sectionIndex + 1 }}
                                        </span>

                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $section->name }}
                                        </h3>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-2">
                                        Section ID: #{{ $section->id }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if ($section->selection_type === 'fixed')
                                        <span
                                            class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Fixed Selection
                                        </span>
                                    @else
                                        <span
                                            class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Custom Selection
                                        </span>
                                    @endif

                                    @if ((int) $section->status === 1)
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Active
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Inactive
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>


                        <div class="p-4">

                            {{-- Section Description --}}
                            @if (filled($section->description))
                                <div class="text-sm text-gray-600 whitespace-pre-line mb-4">
                                    {{ $section->description }}
                                </div>
                            @endif


                            {{-- Section Image --}}
                            @php
                                $sectionImage = $section->getFirstMediaUrl('catering_section_images');
                            @endphp

                            @if ($sectionImage)
                                <div class="mb-5">
                                    <img src="{{ $sectionImage }}" alt="{{ $section->name }}" loading="lazy"
                                        class="w-40 h-28 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif


                            {{-- Selection Rules --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">

                                <div class="rounded-lg bg-gray-50 border border-gray-200 p-3">
                                    <p class="text-xs text-gray-500">Selection Type</p>
                                    <p class="font-semibold text-gray-800 mt-1 capitalize">
                                        {{ $section->selection_type }}
                                    </p>
                                </div>

                                <div class="rounded-lg bg-gray-50 border border-gray-200 p-3">
                                    <p class="text-xs text-gray-500">Minimum Selections</p>
                                    <p class="font-semibold text-gray-800 mt-1">
                                        {{ $section->min_selections }}
                                    </p>
                                </div>

                                <div class="rounded-lg bg-gray-50 border border-gray-200 p-3">
                                    <p class="text-xs text-gray-500">Maximum Selections</p>
                                    <p class="font-semibold text-gray-800 mt-1">
                                        {{ $section->max_selections }}
                                    </p>
                                </div>

                            </div>



                            {{-- Section Menu Items --}}
                            <div class="mt-6">

                                <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">
                                            Menu Items
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Pricing, discounts and package-specific charges.
                                        </p>
                                    </div>

                                    <span
                                        class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-sm font-semibold">
                                        {{ $section->items->count() }} Items
                                    </span>
                                </div>

                                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                                    <table class="w-full min-w-[1100px] text-sm text-left text-gray-600">

                                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                                            <tr>
                                                <th class="px-4 py-4 font-semibold">Menu Item</th>
                                                <th class="px-4 py-4 font-semibold">Unit Price</th>
                                                <th class="px-4 py-4 font-semibold">Discount</th>
                                                <th class="px-4 py-4 font-semibold">Final Price</th>
                                                <th class="px-4 py-4 font-semibold">Extra Price</th>
                                                <th class="px-4 py-4 font-semibold">Price + Extra</th>
                                                <th class="px-4 py-4 font-semibold">Default</th>
                                                <th class="px-4 py-4 font-semibold">Status</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @forelse ($section->items as $packageItem)
                                                @php
                                                    $menuItem = $packageItem->menuItem;

                                                    $unitPrice = (float) ($menuItem?->unit_price ?? 0);
                                                    $discountPrice = (float) ($menuItem?->discount_price ?? 0);

                                                    $finalPrice = max(0, $unitPrice - $discountPrice);

                                                    $discountPercentage =
                                                        $unitPrice > 0
                                                            ? (int) round(($discountPrice / $unitPrice) * 100)
                                                            : 0;

                                                    $hasDiscount = $discountPrice > 0;

                                                    $extraPrice = (float) ($packageItem->extra_price ?? 0);

                                                    $priceWithExtra = max(0, $unitPrice - $discountPrice + $extraPrice);

                                                    $itemImage = $menuItem
                                                        ? $menuItem->image
                                                        : asset('frontend/images/default/menuitem.png');
                                                @endphp

                                                <tr class="bg-white hover:bg-gray-50 transition-colors">

                                                    {{-- Item Image and Name --}}
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center gap-3 min-w-[230px]">

                                                            <img src="{{ $itemImage }}"
                                                                alt="{{ $menuItem?->name ?? 'Menu item' }}"
                                                                loading="lazy"
                                                                class="w-14 h-14 rounded-lg object-cover border border-gray-200 bg-gray-50 shrink-0">

                                                            <div>
                                                                <p class="font-semibold text-gray-900">
                                                                    {{ $menuItem?->name ?? 'Menu Item #' . $packageItem->menu_item_id }}
                                                                </p>

                                                                <p class="text-xs text-gray-400 mt-1">
                                                                    ID: {{ $packageItem->menu_item_id }}
                                                                </p>

                                                              
                                                            </div>

                                                        </div>
                                                    </td>


                                                    {{-- Unit Price --}}
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <span class="font-medium text-gray-800">
                                                            ₹{{ number_format($unitPrice, 2) }}
                                                        </span>
                                                    </td>


                                                    {{-- Discount --}}
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        @if ($hasDiscount)
                                                            <div class="space-y-1">
                                                                <p class="font-semibold text-red-600">
                                                                    −₹{{ number_format($discountPrice, 2) }}
                                                                </p>

                                                                <span
                                                                    class="inline-flex rounded-full bg-red-50 text-red-700 px-2 py-1 text-xs font-semibold">
                                                                    {{ $discountPercentage }}% OFF
                                                                </span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400">
                                                                No discount
                                                            </span>
                                                        @endif
                                                    </td>


                                                    {{-- Final Price --}}
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        @if ($hasDiscount)
                                                            <p class="text-xs text-gray-400 line-through">
                                                                ₹{{ number_format($unitPrice, 2) }}
                                                            </p>
                                                        @endif

                                                        <p class="text-base font-bold text-green-700">
                                                            ₹{{ number_format($finalPrice, 2) }}
                                                        </p>

                                                        <p class="text-xs text-gray-400 mt-1">
                                                            After discount
                                                        </p>
                                                    </td>


                                                    {{-- Package Extra Price --}}
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        @if ($extraPrice > 0)
                                                            <span class="font-semibold text-orange-600">
                                                                +₹{{ number_format($extraPrice, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">
                                                                ₹0.00
                                                            </span>
                                                        @endif
                                                    </td>


                                                    {{-- Final Price Including Extra --}}
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <span class="font-bold text-gray-900">
                                                            ₹{{ number_format($priceWithExtra, 2) }}
                                                        </span>
                                                    </td>


                                                    {{-- Default --}}
                                                    <td class="px-4 py-4">
                                                        @if ($packageItem->is_default)
                                                            <span
                                                                class="inline-flex rounded-full bg-blue-100 text-blue-700 px-2.5 py-1 text-xs font-semibold">
                                                                Default
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">
                                                                No
                                                            </span>
                                                        @endif
                                                    </td>


                                                    {{-- Package Item Status --}}
                                                    <td class="px-4 py-4">
                                                        @if ((int) $packageItem->status === 1)
                                                            <span
                                                                class="inline-flex rounded-full bg-green-100 text-green-700 px-2.5 py-1 text-xs font-semibold">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex rounded-full bg-red-100 text-red-700 px-2.5 py-1 text-xs font-semibold">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </td>

                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="8" class="px-4 py-10 text-center">
                                                        <p class="font-medium text-gray-700">
                                                            No menu items assigned
                                                        </p>

                                                        <p class="text-sm text-gray-400 mt-1">
                                                            Add menu items to this section from the Edit Package page.
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </tbody>

                                    </table>
                                </div>

                                <p class="text-xs text-gray-500 mt-3">
                                    Final Price = max(0, Unit Price − Discount).
                                    Price + Extra = max(0, Unit Price − Discount + Extra Price).
                                </p>

                            </div>


                        </div>
                    </div>

                @empty

                    <div class="text-center py-12">
                        <h3 class="text-lg font-semibold text-gray-700">
                            No sections found
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            This package does not have any sections yet.
                        </p>

                        <a href="{{ route('admin.catering-packages.edit', $package->id) }}"
                            class="inline-block mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                            Add sections
                        </a>
                    </div>
                @endforelse

            </div>
        </div>


        {{-- Record Timestamps --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">
                Record Information
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="text-sm font-medium text-gray-800 mt-1">
                        {{ $package->created_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="text-sm font-medium text-gray-800 mt-1">
                        {{ $package->updated_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

            </div>
        </div>

    </div>
@endsection
