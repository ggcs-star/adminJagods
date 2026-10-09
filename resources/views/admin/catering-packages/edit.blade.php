@extends('admin.app')

@section('content')

    <div class="p-6 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Catering Package</h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">
                    Update package details, sections, and selectable menu items.
                </p>
            </div>

            <a href="{{ route('admin.catering-packages.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-medium text-sm transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Packages
            </a>
        </div>

        {{-- Error Banner Container --}}
        <div id="js-error-container"></div>

        {{-- Server-rendered errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-800 text-sm">Please fix the following errors:</h4>
                        <ul class="mt-1.5 list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @php
            $coverImage = $package->getCoverImage();

            $galleryImages = $package->getMedia('catering_package_images')->reject(function ($media) {
                return (bool) $media->getCustomProperty('is_cover', false);
            });
        @endphp

        <form action="{{ route('admin.catering-packages.update', $package->id) }}" method="POST" id="catering-package-form"
            class="pb-28" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- PACKAGE BASIC DETAILS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Package Information
                    </h4>
                </div>

                <div class="p-5 md:p-6">
                    {{-- Row 1: Name + Slug --}}
                    <div class="grid grid-cols-2 gap-x-5 gap-y-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="package-name" value="{{ old('name', $package->name) }}"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                                URL Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="package-slug" value="{{ old('slug', $package->slug) }}"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                required>
                        </div>
                    </div>

                    {{-- Row 2: Category + Status --}}
                    <div class="grid grid-cols-2 gap-x-5 gap-y-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $package->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Package
                                Status</label>
                            <select name="status"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm py-2.5 px-3.5 transition-all font-medium bg-white">
                                <option value="1" @selected(old('status', $package->status) == 1)>🟢 Active / Visible</option>
                                <option value="0" @selected(old('status', $package->status) == 0)>🔴 Inactive / Hidden</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 3: Description --}}
                    <div class="mb-4">
                        <label
                            class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Description</label>
                        <textarea name="description" rows="2" placeholder="Briefly describe what this package includes..."
                            class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">{{ old('description', $package->description) }}</textarea>
                    </div>

                    {{-- Row 4: Price + Price Type --}}
                    <div class="grid grid-cols-2 gap-x-5 gap-y-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Base
                                Price <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span
                                    class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm"></span>
                                <input type="number" step="0.01" min="0" name="price"
                                    value="{{ old('price', $package->price) }}"
                                    class="w-full rounded-lg shadow-sm text-sm py-2.5 pl-8 pr-3.5 transition-all font-medium text-gray-900 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Price
                                Type <span class="text-red-500">*</span></label>
                            <select name="price_type"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                required>
                                <option value="per_person" @selected(old('price_type', $package->price_type) === 'per_person')>Per Person</option>
                                <option value="fixed" @selected(old('price_type', $package->price_type) === 'fixed')>Fixed Price</option>
                                <option value="per_tray" @selected(old('price_type', $package->price_type) === 'per_tray')>Per Tray</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 5: Min + Max + Lead Time --}}
                    <div class="grid grid-cols-3 gap-x-5 gap-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Min
                                Guests <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="min_guests"
                                value="{{ old('min_guests', $package->min_guests) }}"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Max
                                Guests</label>
                            <input type="number" min="1" name="max_guests"
                                value="{{ old('max_guests', $package->max_guests) }}"
                                class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                placeholder="Unlimited">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Lead
                                Time <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" min="0" name="lead_time_hours"
                                    value="{{ old('lead_time_hours', $package->lead_time_hours) }}"
                                    class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 pr-12 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                                    required>
                                <span
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">HRS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IMAGES SECTION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Package Imagery
                    </h4>
                </div>

                <div class="p-5 md:p-6">
                    <div class="grid grid-cols-2 gap-5">
                        {{-- Cover Image Upload --}}
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200" id="cover-wrapper">

                            <label class="block text-sm font-semibold text-gray-800 mb-0.5">
                                Update Cover Image
                            </label>

                            <p class="text-xs text-gray-500 mb-3">
                                Uploading a new cover will replace the old one.
                            </p>

                            {{-- Existing Cover --}}
                            <div id="existing-cover-preview"
                                class="{{ $coverImage ? '' : 'hidden' }} relative w-full h-48 group rounded-xl overflow-hidden border-2 border-indigo-100 shadow-md">
                                @if ($coverImage)
                                    <img src="{{ $coverImage->getUrl() }}" alt="{{ $package->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="" alt="Cover" class="w-full h-full object-cover">
                                @endif

                                <div
                                    class="absolute inset-0 bg-gray-900 bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" onclick="removeExistingCover()"
                                        style="background-color: #dc2626; color: #ffffff;"
                                        class="px-3 py-1.5 rounded-lg hover:opacity-90 text-xs font-semibold
                                          flex items-center gap-1.5 shadow-lg">

                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>

                                        Remove Cover
                                    </button>
                                </div>

                                <div
                                    class="absolute top-2.5 left-2.5 bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded font-bold shadow-sm uppercase tracking-wider">
                                    Current Cover
                                </div>
                            </div>

                            {{-- Upload Dropzone --}}
                            <div id="cover-dropzone"
                                class="{{ $coverImage ? 'hidden' : '' }} flex flex-col items-center justify-center py-6 px-4 border-2 border-indigo-200 border-dashed rounded-xl bg-white hover:bg-indigo-50 transition-all cursor-pointer group"
                                onclick="document.getElementById('cover-image').click()">
                                <div class="p-2.5 bg-indigo-100 rounded-full group-hover:scale-110 transition-transform">
                                    <svg class="h-6 w-6 text-indigo-500" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <span class="mt-2 font-semibold text-indigo-600 text-sm">
                                    Click to upload cover
                                </span>

                                <span class="text-xs text-gray-400 mt-0.5">
                                    PNG, JPG (Max 5MB)
                                </span>
                            </div>

                            <input id="cover-image" name="cover_image" type="file" class="sr-only" accept="image/*"
                                onchange="handleCoverImage(event)">

                            {{-- New Cover Preview --}}
                            <div id="cover-preview-container"
                                class="hidden relative w-full h-48 group rounded-xl overflow-hidden border-2 border-green-100 shadow-md">
                                <img id="cover-img-src" src="" class="w-full h-full object-cover"
                                    alt="New Cover">

                                <div
                                    class="absolute inset-0 bg-gray-900 bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" onclick="removeCoverImage()"
                                        class="bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-600 text-xs font-semibold flex items-center gap-1.5 shadow-lg">
                                        Remove
                                    </button>
                                </div>

                                <div
                                    class="absolute top-2.5 left-2.5 bg-green-600 text-white text-[10px] px-2 py-0.5 rounded font-bold shadow-sm uppercase tracking-wider">
                                    New Cover
                                </div>
                            </div>

                            <p class="js-error-msg mt-1.5 text-xs text-red-500 font-medium hidden"
                                data-error-for="cover_image"></p>

                        </div>

                        {{-- Gallery Images --}}
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200" id="gallery-wrapper">
                            <label class="block text-sm font-semibold text-gray-800 mb-0.5">Add More Gallery Images</label>
                            <p class="text-xs text-gray-500 mb-3">These will be added alongside existing photos.</p>

                            <div class="flex flex-col items-center justify-center py-6 px-4 border-2 border-gray-300 border-dashed rounded-xl bg-white hover:bg-gray-50 hover:border-gray-400 transition-all cursor-pointer group"
                                onclick="document.getElementById('package-images').click()">
                                <div class="p-2.5 bg-gray-100 rounded-full group-hover:scale-110 transition-transform">
                                    <svg class="h-6 w-6 text-gray-500" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span
                                    class="mt-2 font-semibold text-gray-600 group-hover:text-gray-900 transition-colors text-sm">Select
                                    multiple images</span>
                                <span class="text-xs text-gray-400 mt-0.5">PNG, JPG (Max 5MB)</span>
                            </div>
                            <input id="package-images" name="images[]" type="file" class="sr-only" multiple
                                accept="image/*" onchange="handleGalleryImages(event)">

                            <div id="gallery-preview-container" class="flex flex-wrap gap-2 mt-3"></div>

                            <p class="js-error-msg mt-1.5 text-xs text-red-500 font-medium hidden"
                                data-error-for="images"></p>
                            {{-- Existing Gallery Images (cover excluded) --}}
                            @if ($galleryImages->count())
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mt-5">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        Existing Gallery Images
                                    </label>

                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($galleryImages as $media)
                                            <div class="relative w-20 h-20 group rounded-lg
                                                    border border-gray-200 shadow-sm"
                                                id="existing-media-{{ $media->id }}">

                                                <img src="{{ $media->getUrl() }}" alt="Package Gallery"
                                                    class="w-full h-full object-cover rounded-lg">

                                                {{-- Always Visible Red Remove Button --}}
                                                <button type="button" onclick="removeExistingMedia({{ $media->id }})"
                                                    style="background-color: #dc2626; color: #ffffff; border: 2px solid #ffffff;"
                                                    class="absolute -top-2 -right-2 z-20 flex items-center justify-center
           w-6 h-6 rounded-full hover:opacity-90 shadow-md"
                                                    title="Remove Image">

                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>
            </div>

            {{-- SECTIONS --}}
            <div class="mb-6 relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Menu Sections
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Group items into Starters, Main Course, Desserts, etc.</p>
                    </div>

                    <button type="button" onclick="addSection()"
                        style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 16px; background-color: #dc2626; color: white; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.15); cursor: pointer; border: none; transition: all 0.2s;">

                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2.5" d="M12 4v16m8-8H4">
                            </path>
                        </svg>

                        Add New Section
                    </button>
                </div>

                <div id="sections-container" class="space-y-5"></div>

                <div id="sections-empty-state"
                    class="bg-white rounded-2xl border-2 border-dashed border-gray-300 p-10 text-center transition-all hover:bg-gray-50">
                    <div class="mx-auto w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-gray-800">No sections added yet</h4>
                    <p class="text-sm text-gray-500 mt-1.5 mb-5 max-w-sm mx-auto">Build your package by adding sections.
                        Each section will contain the actual menu items.</p>

                    <button type="button" onclick="addSection()"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-md transition-all hover:-translate-y-0.5 cursor-pointer relative z-20">
                        Create First Section
                    </button>
                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div
                class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] sm:static sm:bg-transparent sm:border-0 sm:shadow-none p-4 sm:p-0">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 max-w-7xl mx-auto sm:bg-white sm:p-4 sm:shadow-lg sm:rounded-2xl sm:border sm:border-gray-200">
                    <a href="{{ route('admin.catering-packages.index') }}"
                        class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 text-center transition-all text-sm">
                        Cancel
                    </a>
                    <button type="submit" id="submit-button"
                        style="padding: 10px 24px; background-color: #dc2626; color: white; border-radius: 8px; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 14px; border: none; transition: all 0.2s;">

                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>

                        Update Package
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- MENU ITEM TEMPLATE (Hidden) --}}
    <select id="menu-items-template" class="hidden">
        <option value="">-- Select Menu Item --</option>
        @foreach ($menuItems as $item)
            <option value="{{ $item->id }}">
                {{ $item->name }} @if (isset($item->unit_price))
                    — ₹{{ number_format((float) $item->unit_price, 2) }}
                @endif
            </option>
        @endforeach
    </select>

@endsection

@push('scripts')
    <script>
        let sectionIndex = 0;
        let slugManuallyChanged = true;

        // ==========================================
        // Existing Media Handling (gallery)
        // ==========================================
        function removeExistingMedia(mediaId) {
            const container = document.getElementById('existing-media-' + mediaId);
            if (!container) return;
            container.style.opacity = '0';
            container.style.transform = 'scale(0.9)';

            setTimeout(() => {
                container.style.display = 'none';
                const form = document.getElementById('catering-package-form');
                const exists = form.querySelector(`input[name="remove_media[]"][value="${mediaId}"]`);
                if (!exists) {
                    form.insertAdjacentHTML('beforeend',
                        `<input type="hidden" name="remove_media[]" value="${mediaId}">`);
                }
            }, 200);
        }

        // ==========================================
        // Existing Cover Remove
        // ==========================================
        function removeExistingCover() {
            const existingCover = document.getElementById('existing-cover-preview');
            if (existingCover) {
                existingCover.classList.add('hidden');
            }

            const dropzone = document.getElementById('cover-dropzone');
            if (dropzone) {
                dropzone.classList.remove('hidden');
            }

            const form = document.getElementById('catering-package-form');

            @if ($coverImage)
                const coverMediaId = {{ $coverImage->id }};

                const existingInput = form.querySelector(
                    `input[name="remove_media[]"][value="${coverMediaId}"]`
                );

                if (!existingInput) {
                    form.insertAdjacentHTML(
                        'beforeend',
                        `<input type="hidden" name="remove_media[]" value="${coverMediaId}">`
                    );
                }
            @endif
        }

        // ==========================================
        // Cover Image Logic
        // ==========================================
        function handleCoverImage(event) {
            const file = event.target.files[0];

            if (!file || !file.type.match('image.*')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = (e) => {

                // Hide existing cover
                const existingCover = document.getElementById('existing-cover-preview');
                if (existingCover) {
                    existingCover.classList.add('hidden');
                }

                // Hide upload dropzone
                const dropzone = document.getElementById('cover-dropzone');
                if (dropzone) {
                    dropzone.classList.add('hidden');
                }

                // Show new cover
                document.getElementById('cover-img-src').src = e.target.result;

                document
                    .getElementById('cover-preview-container')
                    .classList.remove('hidden');
            };

            reader.readAsDataURL(file);

            clearFieldError('cover_image');
        }

        function removeCoverImage() {
            document.getElementById('cover-image').value = '';
            document.getElementById('cover-img-src').src = '';
            document.getElementById('cover-preview-container').classList.add('hidden');

            // Bring back existing cover if present
            const existingCover = document.getElementById('existing-cover-preview');
            if (existingCover && !existingCover.classList.contains('hidden')) {
                // was already visible? keep visible
            } else if (existingCover) {
                // check if it had content originally
            }

            // If existing cover markup exists with an actual image → show it back
            const existingImg = existingCover?.querySelector('img');
            if (existingImg && existingImg.getAttribute('src')) {
                existingCover.classList.remove('hidden');
            } else {
                // No existing cover — show dropzone
                document.getElementById('cover-dropzone').classList.remove('hidden');
            }
        }

        // ==========================================
        // Gallery Images Logic
        // ==========================================
        let selectedGalleryFiles = new DataTransfer();

        function handleGalleryImages(event) {
            event.stopPropagation();
            const files = event.target.files;
            const previewContainer = document.getElementById('gallery-preview-container');
            const fileInput = document.getElementById('package-images');

            Array.from(files).forEach((file) => {
                if (!file.type.match('image.*')) return;
                const uniqueId = file.name + '-' + file.lastModified;

                let isDuplicate = false;
                Array.from(selectedGalleryFiles.files).forEach(f => {
                    if ((f.name + '-' + f.lastModified) === uniqueId) isDuplicate = true;
                });

                if (!isDuplicate) {
                    selectedGalleryFiles.items.add(file);

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewHtml = `
                            <div class="relative w-16 h-16 group rounded-lg overflow-hidden border border-gray-200 shadow-sm" id="img-preview-${uniqueId.replace(/[^a-zA-Z0-9]/g, '')}">
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gray-900 bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" onclick="removeGalleryImage('${uniqueId}')" class="bg-red-500 text-white p-1 rounded-full hover:bg-red-600 shadow-md" title="Remove">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewContainer.insertAdjacentHTML('beforeend', previewHtml);
                    };
                    reader.readAsDataURL(file);
                }
            });

            fileInput.files = selectedGalleryFiles.files;
            clearFieldError('images');
        }

        function removeGalleryImage(uniqueId) {
            const fileInput = document.getElementById('package-images');
            const newFiles = new DataTransfer();

            Array.from(selectedGalleryFiles.files).forEach(file => {
                if ((file.name + '-' + file.lastModified) !== uniqueId) {
                    newFiles.items.add(file);
                }
            });

            selectedGalleryFiles = newFiles;
            fileInput.files = selectedGalleryFiles.files;

            const previewId = `img-preview-${uniqueId.replace(/[^a-zA-Z0-9]/g, '')}`;
            const previewElement = document.getElementById(previewId);
            if (previewElement) previewElement.remove();
        }

        // ==========================================
        // Slug Generator
        // ==========================================
        const packageNameInput = document.getElementById('package-name');
        const packageSlugInput = document.getElementById('package-slug');

        packageSlugInput.addEventListener('input', function() {
            slugManuallyChanged = true;
        });

        packageNameInput.addEventListener('input', function() {
            if (slugManuallyChanged) return;
            const slug = this.value.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(
                /-+/g, '-');
            packageSlugInput.value = slug;
        });

        function updateEmptyState() {
            const container = document.getElementById('sections-container');
            const emptyState = document.getElementById('sections-empty-state');
            emptyState.style.display = container.children.length === 0 ? 'block' : 'none';
        }

        // ==========================================
        // Sections Logic (with key preservation)
        // ==========================================
        function addSection(data = null, existingKey = null) {
            const sectionKey = existingKey !== null && existingKey !== undefined ?
                existingKey :
                'sec_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
            sectionIndex++;

            const section = data || {
                id: '',
                name: '',
                description: '',
                selection_type: 'fixed',
                min_selections: 1,
                max_selections: 1,
                status: 1,
                items: [],
                image_url: null
            };

            const existingImageHtml = section.image_url ? `
                <div class="mt-2 flex items-center gap-2 p-1.5 bg-white border border-gray-200 rounded-lg w-max shadow-sm">
                    <img src="${section.image_url}" class="h-9 w-9 object-cover rounded shadow-sm border border-gray-100">
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Current</span>
                </div>
            ` : '';

            const container = document.getElementById('sections-container');

            const sectionHtml = `
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" id="section-${sectionKey}">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-5 py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-800 text-base section-heading">${escapeHtml(section.name) || 'New Section'}</h4>
                    </div>
                    <button type="button" onclick="removeSection('${sectionKey}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Remove
                    </button>
                </div>

                <div class="p-5">
                    <input type="hidden" name="sections[${sectionKey}][id]" value="${section.id || ''}">

                    <div class="grid grid-cols-2 gap-x-5 gap-y-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Section Name <span class="text-red-500">*</span></label>
                            <input type="text" name="sections[${sectionKey}][name]" value="${escapeHtml(section.name || '')}" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" placeholder="e.g. Welcome Drinks" oninput="updateSectionHeading('${sectionKey}', this.value)" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Status</label>
                            <select name="sections[${sectionKey}][status]" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                <option value="1" ${section.status == 1 ? 'selected' : ''}>🟢 Active</option>
                                <option value="0" ${section.status == 0 ? 'selected' : ''}>🔴 Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Section Image <span class="text-xs text-gray-400 font-normal normal-case">(Optional)</span></label>
                        <input type="file" name="sections[${sectionKey}][image]" accept="image/*" class="w-full rounded-lg shadow-sm text-sm py-2 px-3 cursor-pointer border-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                        ${existingImageHtml}
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Description <span class="text-xs text-gray-400 font-normal normal-case">(Optional)</span></label>
                        <textarea name="sections[${sectionKey}][description]" rows="2" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" placeholder="Brief description for this section...">${escapeHtml(section.description || '')}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-x-5 gap-y-4 mb-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Rule Type <span class="text-red-500">*</span></label>
                            <select name="sections[${sectionKey}][selection_type]" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all bg-gray-50 border-gray-300 focus:border-indigo-500" required>
                                <option value="fixed" ${section.selection_type === 'fixed' ? 'selected' : ''}>Fixed (Pre-decided)</option>
                                <option value="custom" ${section.selection_type === 'custom' ? 'selected' : ''}>Custom (User selects)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Min Selectable <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="sections[${sectionKey}][min_selections]" value="${section.min_selections ?? 1}" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Max Selectable <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="sections[${sectionKey}][max_selections]" value="${section.max_selections ?? 1}" class="w-full rounded-lg shadow-sm text-sm py-2.5 px-3.5 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" required>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-gray-100 px-4 py-2.5 border-b border-gray-200 flex justify-between items-center">
                            <h5 class="text-xs font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                Menu Items Included
                            </h5>
                           <button type="button" onclick="addItem('${sectionKey}')"
    style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background-color: #dc2626; color: white; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: all 0.2s;">
    
    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="3" d="M12 4v16m8-8H4">
        </path>
    </svg>

    Add Item
</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr class="text-xs uppercase text-gray-500 border-b border-gray-200">
                                        <th class="text-left py-2.5 px-4 font-semibold">Menu Item <span class="text-red-500">*</span></th>
                                        <th class="text-left py-2.5 px-4 w-36 font-semibold">Extra Price (₹)</th>
                                        <th class="text-center py-2.5 px-4 w-24 font-semibold">Default</th>
                                        <th class="text-right py-2.5 px-4 w-16 font-semibold"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-container-${sectionKey}" class="divide-y divide-gray-100 bg-white"></tbody>
                            </table>
                            <div id="items-empty-${sectionKey}" class="text-center text-xs text-gray-400 py-5 hidden bg-white">
                                No menu items added to this section yet.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
            container.insertAdjacentHTML('beforeend', sectionHtml);

            if (section.items && typeof section.items === 'object') {
                const itemsArr = Array.isArray(section.items) ?
                    section.items :
                    Object.entries(section.items).map(([k, v]) => ({
                        key: k,
                        ...v
                    }));

                if (itemsArr.length) {
                    itemsArr.forEach(item => addItem(sectionKey, item, item.key || null));
                } else {
                    addItem(sectionKey);
                }
            } else {
                addItem(sectionKey);
            }
            updateEmptyState();
        }

        function removeSection(sectionKey) {
            const section = document.getElementById(`section-${sectionKey}`);
            if (section && confirm('Are you sure you want to remove this section and all its items?')) {
                section.style.opacity = '0';
                section.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    section.remove();
                    updateEmptyState();
                }, 200);
            }
        }

        function addItem(secKey, item = null, existingItemKey = null) {
            const container = document.getElementById(`items-container-${secKey}`);
            const itemKey = existingItemKey || 'itm_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
            const optionsHtml = document.getElementById('menu-items-template').innerHTML;

            const isChecked = (item?.is_default == 1 || item?.is_default === '1' || item?.is_default === true) ? 'checked' :
                '';

            const itemHtml = `
            <tr id="item-${secKey}-${itemKey}" class="hover:bg-gray-50 transition-colors">
                <td class="py-2 px-4 align-top">
                    <select name="sections[${secKey}][items][${itemKey}][menu_item_id]" class="w-full rounded-lg shadow-sm text-sm py-2 px-3 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td class="py-2 px-4 align-top">
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium"></span>
                        <input type="number" step="0.01" min="0" name="sections[${secKey}][items][${itemKey}][extra_price]" value="${item?.extra_price ?? 0}" class="w-full rounded-lg shadow-sm text-sm py-2 pl-6 pr-2 transition-all border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    </div>
                </td>
                <td class="py-2 px-4 text-center align-top pt-3.5">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[${secKey}][items][${itemKey}][is_default]" value="1" ${isChecked} class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    </label>
                </td>
                <td class="py-2 px-4 text-right align-top">
                    <button type="button" onclick="removeItem('${secKey}', '${itemKey}')" class="mt-0.5 text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors cursor-pointer" title="Remove Item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <input type="hidden" name="sections[${secKey}][items][${itemKey}][status]" value="1">
                </td>
            </tr>
            `;
            container.insertAdjacentHTML('beforeend', itemHtml);

            if (item?.menu_item_id) {
                const row = document.getElementById(`item-${secKey}-${itemKey}`);
                row.querySelector('select').value = item.menu_item_id;
            }

            const emptyEl = document.getElementById(`items-empty-${secKey}`);
            if (emptyEl) emptyEl.style.display = 'none';
        }

        function removeItem(secKey, itemKey) {
            const row = document.getElementById(`item-${secKey}-${itemKey}`);
            if (!row) return;
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                const container = document.getElementById(`items-container-${secKey}`);
                if (container.children.length === 0) {
                    document.getElementById(`items-empty-${secKey}`).style.display = 'block';
                }
            }, 150);
        }

        function updateSectionHeading(sectionKey, value) {
            const section = document.getElementById(`section-${sectionKey}`);
            if (section) section.querySelector('.section-heading').textContent = value.trim() || 'New Section';
        }

        function escapeHtml(value) {
            if (!value) return '';
            return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ==========================================
        // Validation Error Display (AJAX)
        // ==========================================
        function clearAllErrors() {
            document.querySelectorAll('.js-error-msg').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            document.querySelectorAll('.js-inline-err').forEach(el => el.remove());
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-400', 'bg-red-50/30', 'focus:border-red-500', 'focus:ring-red-200');
            });
            const c = document.getElementById('js-error-container');
            if (c) c.innerHTML = '';
        }

        function clearFieldError(fieldName) {
            const errEl = document.querySelector(`.js-error-msg[data-error-for="${fieldName}"]`);
            if (errEl) {
                errEl.classList.add('hidden');
                errEl.textContent = '';
            }
        }

        function dotToNameAttr(field) {
            if (field.includes('*')) return null;
            const parts = field.split('.');
            if (parts.length === 1) return parts[0];
            return parts[0] + parts.slice(1).map(p => `[${p}]`).join('');
        }

        function applyValidationErrors(errors) {
            clearAllErrors();
            const flatMessages = [];

            Object.entries(errors).forEach(([field, messages]) => {
                messages.forEach(m => flatMessages.push(m));

                if (field === 'cover_image') {
                    const errEl = document.querySelector('.js-error-msg[data-error-for="cover_image"]');
                    if (errEl) {
                        errEl.textContent = messages[0];
                        errEl.classList.remove('hidden');
                    }
                    document.getElementById('cover-wrapper')?.classList.add('border-red-300', 'bg-red-50');
                    return;
                }

                if (field.startsWith('images')) {
                    const errEl = document.querySelector('.js-error-msg[data-error-for="images"]');
                    if (errEl) {
                        errEl.textContent = messages[0];
                        errEl.classList.remove('hidden');
                    }
                    document.getElementById('gallery-wrapper')?.classList.add('border-red-300', 'bg-red-50');
                    return;
                }

                const nameAttr = dotToNameAttr(field);
                if (!nameAttr) return;

                const inputs = document.querySelectorAll(`[name="${CSS.escape(nameAttr)}"]`);
                inputs.forEach(input => {
                    input.classList.add('border-red-400', 'bg-red-50/30', 'focus:border-red-500',
                        'focus:ring-red-200');

                    const next = input.nextElementSibling;
                    if (next && next.classList.contains('js-inline-err')) {
                        next.textContent = messages[0];
                    } else {
                        const p = document.createElement('p');
                        p.className = 'js-inline-err mt-1 text-xs text-red-500 font-medium';
                        p.textContent = messages[0];
                        input.insertAdjacentElement('afterend', p);
                    }
                });
            });

            const container = document.getElementById('js-error-container');
            if (container) {
                container.innerHTML = `
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-red-800 text-sm">Please fix the following errors:</h4>
                                <ul class="mt-1.5 list-disc list-inside text-xs text-red-700 space-y-1">
                                    ${flatMessages.map(e => `<li>${escapeHtml(e)}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
                container.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // ==========================================
        // AJAX Form Submission
        // ==========================================
        document.getElementById('catering-package-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const sections = document.querySelectorAll('#sections-container > div');
            if (sections.length === 0) {
                alert('Please add at least one package section before saving.');
                return;
            }

            const form = this;
            const submitButton = document.getElementById('submit-button');
            const originalHTML = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML =
                `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...`;

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                if (response.status === 422) {
                    const data = await response.json();
                    applyValidationErrors(data.errors || {});
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalHTML;
                    return;
                }

                if (response.ok || response.redirected) {
                    window.location.href = '{{ route('admin.catering-packages.index') }}';
                    return;
                }

                const text = await response.text();
                console.error('Unexpected response:', response.status, text);
                alert('Something went wrong. Please try again. (Status: ' + response.status + ')');
                submitButton.disabled = false;
                submitButton.innerHTML = originalHTML;

            } catch (err) {
                console.error('Submit error:', err);
                alert('Network error. Please check your connection and try again.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalHTML;
            }
        });

        // ==========================================
        // Data Recovery on Load
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const oldSections = @json(old('sections'));
            const dbSections = @json($package->sections);

            const sectionImageMap = {};
            @foreach ($package->sections as $dbSection)
                @if ($dbSection->hasMedia('catering_section_images'))
                    sectionImageMap['{{ $dbSection->id }}'] =
                        '{{ $dbSection->getFirstMediaUrl('catering_section_images') }}';
                @endif
            @endforeach

            if (oldSections && typeof oldSections === 'object' && Object.keys(oldSections).length > 0) {
                Object.entries(oldSections).forEach(([secKey, section]) => {
                    if (!section) return;

                    const numericKey = parseInt(secKey, 10);
                    if (!isNaN(numericKey) && numericKey >= sectionIndex) {
                        sectionIndex = numericKey + 1;
                    }

                    let itemsArr = [];
                    if (section.items && typeof section.items === 'object') {
                        itemsArr = Object.entries(section.items).map(([k, v]) => ({
                            key: k,
                            ...v
                        }));
                    }

                    addSection({
                        id: section.id || '',
                        name: section.name || '',
                        description: section.description || '',
                        status: section.status ?? 1,
                        selection_type: section.selection_type ?? 'fixed',
                        min_selections: section.min_selections ?? 1,
                        max_selections: section.max_selections ?? 1,
                        image_url: section.id ? (sectionImageMap[section.id] || null) : null,
                        items: itemsArr
                    }, secKey);
                });
            } else if (dbSections && dbSections.length > 0) {
                dbSections.forEach((section) => {
                    addSection({
                        id: section.id || '',
                        name: section.name || '',
                        description: section.description || '',
                        status: section.status ?? 1,
                        selection_type: section.selection_type ?? 'fixed',
                        min_selections: section.min_selections ?? 1,
                        max_selections: section.max_selections ?? 1,
                        image_url: sectionImageMap[section.id] || null,
                        items: section.items || []
                    });
                });
            } else {
                addSection();
            }
            updateEmptyState();
        });
    </script>
@endpush
