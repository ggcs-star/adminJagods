@extends('admin.app')

@section('content')
    <div class="row">
        {{-- Breadcrumb --}}
        <div class="col-12 mb-3">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('menu-items/edit') }}
            </div>
        </div>

        <div class="col-12">
            <div class="db-card">
                {{-- Card Header with Back Button --}}
                <div class="db-card-header flex justify-between items-center bg-gray-50 border-b pb-3 mb-4 rounded-t-lg">
                    <h3 class="db-card-title text-xl font-semibold">{{ __('restaurant.menu_item') }} (Edit)</h3>
                    <button type="button" onclick="window.history.back();" class="db-btn text-white rounded px-3 py-2 transition-all hover:opacity-90" style="background-color: #6c757d;">
                        <i class="fa-solid fa-arrow-left mr-1"></i>
                        <span>Back</span>
                    </button>
                </div>

                <div class="db-card-body p-4">
                    <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data" id="menu-item-form">
                        @csrf
                        @method('PUT')
                        
                        {{-- ================= SECTION 1: BASIC DETAILS ================= --}}
                        <div class="mb-5 border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4"><i class="fa-solid fa-circle-info mr-2 text-primary"></i>Basic Details</h4>
                            <div class="row gap-y-4">
                                
                                @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                                    <div class="col-12 sm:col-6 md:col-4">
                                        <label class="db-field-title required" for="area">{{ __('levels.restaurant') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="restaurant_id" id="area" class="db-field-control !appearance-none select2 custom-select2 @error('restaurant_id') invalid @enderror">
                                                <option value="">---</option>
                                                @if (!blank($restaurants))
                                                    @foreach ($restaurants as $restaurant)
                                                        <option value="{{ $restaurant->id }}" {{ old('restaurant_id', $menuItem->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                                            {{ $restaurant->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('restaurant_id') <small class="db-field-alert">{{ $message }}</small> @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant->id ?? 0 }}">
                                @endif

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
                                    <input type="text" name="name" id="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name', $menuItem->name) }}">
                                    @error('name') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title required" for="module_id">Module</label>
                                    <select name="module_id" id="module_id" class="db-field-control @error('module_id') invalid @enderror" required>
                                        <option value="">Select Module</option>
                                        @foreach ($modules as $id => $slug)
                                            <option value="{{ $id }}" {{ old('module_id', $menuItem->module_id) == $id ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $slug)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('module_id') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title" for="categories">{{ __('restaurant.categories') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="categories[]" id="categories" class="db-field-control appearance-none select2 custom-select2 @error('categories') invalid @enderror" multiple="multiple">
                                            @if (!blank($categories))
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id, $menuItem_categories) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('categories') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title" for="restroType">Item Type</label>
                                    <select name="restroType" id="restroType" class="db-field-control @error('restroType') invalid @enderror">
                                        <option value="">Select Type</option>
                                        <option value="veg" {{ old('restroType', $menuItem->restroType ?? '') == 'veg' ? 'selected' : '' }}>Veg</option>
                                        <option value="non-veg" {{ old('restroType', $menuItem->restroType ?? '') == 'non-veg' ? 'selected' : '' }}>Non-Veg</option>
                                    </select>
                                    @error('restroType') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title required">{{ __('levels.status') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
                                            <option value="">---</option>
                                            @foreach (trans('statuses') as $key => $status)
                                                <option value="{{ $key }}" {{ old('status', $menuItem->status) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('status') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                @php
                                    use App\Enums\MenuItemTag;
                                    $selectedTags = old('tags', is_array($menuItem->tags) ? $menuItem->tags : (is_string($menuItem->tags) ? json_decode($menuItem->tags, true) : []));
                                @endphp
                                
                                <div class="col-12 md:col-8">
                                    <label class="db-field-title" for="tags">Tags</label>
                                    <select name="tags[]" id="tags" class="db-field-control select2 custom-select2 @error('tags') invalid @enderror" multiple="multiple">
                                        @foreach (MenuItemTag::all() as $tag)
                                            <option value="{{ $tag }}" {{ in_array($tag, (array) $selectedTags) ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('-', ' ', $tag)) }}
                                            </option>
                                        @endforeach
                                        @foreach ((array) $selectedTags as $tag)
                                            @if (!in_array($tag, MenuItemTag::all()))
                                                <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('tags') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ================= SECTION 2: PRICING & LIMITS ================= --}}
                        <div class="mb-5 border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4"><i class="fa-solid fa-indian-rupee-sign mr-2 text-primary"></i>Pricing & Limits</h4>
                            <div class="row gap-y-4">
                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title required" for="unit_price">{{ __('levels.unit_price') }}</label>
                                    <input type="text" name="unit_price" id="unit_price" class="db-field-control @error('unit_price') invalid @enderror" value="{{ old('unit_price', $menuItem->unit_price) }}">
                                    @error('unit_price') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title" for="discount_price">{{ __('levels.discount_price') }}</label>
                                    <input type="text" name="discount_price" id="discount_price" class="db-field-control @error('discount_price') invalid @enderror" value="{{ old('discount_price', $menuItem->discount_price) }}">
                                    @error('discount_price') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 sm:col-6 md:col-4">
                                    <label class="db-field-title" for="max_cart_quantity">Max Cart Quantity</label>
                                    <input type="number" min="1" name="max_cart_quantity" id="max_cart_quantity" class="db-field-control @error('max_cart_quantity') invalid @enderror" value="{{ old('max_cart_quantity', $menuItem->max_cart_quantity ?? '') }}">
                                    @error('max_cart_quantity') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ================= SECTION 3: MEDIA (NEW UNIFIED UI) ================= --}}
                        <div class="mb-5 border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4"><i class="fa-regular fa-images mr-2 text-primary"></i>Media & Images</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- ---------------- COLUMN 1: ITEM IMAGES ---------------- --}}
                                <div>
                                    <label class="db-field-title">{{ __('levels.image') }}</label>
                                    
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mt-1 shadow-sm">
                                        {{-- Header --}}
                                        <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gray-50/50">
                                            <h5 class="text-sm font-semibold text-gray-800">Item Gallery</h5>
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-semibold">
                                                <span id="item-image-count-text">{{ $menuItem->getMedia('menu-items')->count() }} Images</span>
                                            </div>
                                        </div>

                                        {{-- Dropzone --}}
                                        <div class="p-4 border-b border-gray-200">
                                            <div id="item-image-dropzone" class="relative group border-2 border-dashed border-gray-300 rounded-xl bg-white hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-200 cursor-pointer">
                                                <input type="file" id="item-images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                                <div class="px-4 py-6 text-center pointer-events-none">
                                                    <div class="mx-auto w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition-colors">
                                                        <i class="fa-solid fa-cloud-arrow-up text-indigo-600 text-xl"></i>
                                                    </div>
                                                    <h5 class="text-sm font-semibold text-gray-800">Upload Images</h5>
                                                    <p class="text-xs text-gray-500 mt-1"><span class="font-medium text-indigo-600">Click to browse</span> or drag & drop</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Gallery Area --}}
                                        <div class="p-4 bg-gray-50/30">
                                            <div id="item-images-empty" class="{{ $menuItem->getMedia('menu-items')->count() > 0 ? 'hidden' : '' }} border border-dashed border-gray-200 rounded-xl py-6 px-4 text-center bg-white">
                                                <p class="text-xs font-medium text-gray-400">No item images added</p>
                                            </div>

                                            <div id="item-images-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                @foreach ($menuItem->getMedia('menu-items') as $media)
                                                    <div id="existing-item-{{ $media->id }}" class="item-existing-image relative group rounded-xl overflow-hidden border border-gray-200 bg-white aspect-square shadow-sm" data-media-id="{{ $media->id }}">
                                                        <img src="{{ $media->getUrl() }}" alt="{{ $menuItem->name }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                                        <div class="absolute top-1.5 left-1.5">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-black/60 text-white text-[9px] font-medium backdrop-blur-sm">Existing</span>
                                                        </div>
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                                            <button type="button" onclick="removeExistingItemMedia({{ $media->id }})" class="w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow-lg transform hover:scale-110 transition-all" title="Remove image">
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('images') <small class="db-field-alert mt-2 block">{{ $message }}</small> @enderror
                                    @error('images.*') <small class="db-field-alert mt-2 block">{{ $message }}</small> @enderror
                                </div>

                                {{-- ---------------- COLUMN 2: COVER IMAGES ---------------- --}}
                                <div>
                                    <label class="db-field-title">Cover Images</label>
                                    
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mt-1 shadow-sm">
                                        {{-- Header --}}
                                        <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gray-50/50">
                                            <h5 class="text-sm font-semibold text-gray-800">Cover Gallery</h5>
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-semibold">
                                                <span id="cover-image-count-text">{{ $menuItem->getMedia('menu-item-covers')->count() }} Covers</span>
                                            </div>
                                        </div>

                                        {{-- Dropzone --}}
                                        <div class="p-4 border-b border-gray-200">
                                            <div id="cover-image-dropzone" class="relative group border-2 border-dashed border-gray-300 rounded-xl bg-white hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-200 cursor-pointer">
                                                <input type="file" id="cover-images" name="cover_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                                <div class="px-4 py-6 text-center pointer-events-none">
                                                    <div class="mx-auto w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition-colors">
                                                        <i class="fa-regular fa-image text-indigo-600 text-xl"></i>
                                                    </div>
                                                    <h5 class="text-sm font-semibold text-gray-800">Upload Cover Images</h5>
                                                    <p class="text-xs text-gray-500 mt-1"><span class="font-medium text-indigo-600">Click to browse</span> or drag & drop</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Gallery Area --}}
                                        <div class="p-4 bg-gray-50/30">
                                            <div id="cover-images-empty" class="{{ $menuItem->getMedia('menu-item-covers')->count() > 0 ? 'hidden' : '' }} border border-dashed border-gray-200 rounded-xl py-6 px-4 text-center bg-white">
                                                <p class="text-xs font-medium text-gray-400">No cover images added</p>
                                            </div>

                                            <div id="cover-images-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                @foreach ($menuItem->getMedia('menu-item-covers') as $media)
                                                    <div id="existing-cover-{{ $media->id }}" class="cover-existing-image relative group rounded-xl overflow-hidden border border-gray-200 bg-white aspect-square shadow-sm" data-media-id="{{ $media->id }}">
                                                        <img src="{{ $media->getUrl() }}" alt="{{ $menuItem->name }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                                        <div class="absolute top-1.5 left-1.5">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-black/60 text-white text-[9px] font-medium backdrop-blur-sm">Existing</span>
                                                        </div>
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                                            <button type="button" onclick="removeExistingCoverMedia({{ $media->id }})" class="w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow-lg transform hover:scale-110 transition-all" title="Remove cover">
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('cover_images') <small class="db-field-alert mt-2 block">{{ $message }}</small> @enderror
                                    @error('cover_images.*') <small class="db-field-alert mt-2 block">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ================= SECTION 4: DESCRIPTION ================= --}}
                        <div class="mb-5 border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4"><i class="fa-solid fa-align-left mr-2 text-primary"></i>Description</h4>
                            <div class="row">
                                <div class="col-12">
                                    <textarea name="description" class="db-field-control @error('description') invalid @enderror" id="editor" cols="30" rows="6">{{ old('description', $menuItem->description) }}</textarea>
                                    @error('description') <small class="db-field-alert">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ================= ACTIONS ================= --}}
                        <div class="flex flex-wrap gap-3 mt-5">
                            <button type="submit" class="db-btn text-white bg-primary rounded px-5 py-2 hover:opacity-90 transition-all flex items-center">
                                <i class="fa-solid fa-circle-check mr-2"></i>
                                <span>{{ __('levels.save') }} Changes</span>
                            </button>
                            <button type="button" onclick="window.history.back();" class="db-btn text-white rounded px-5 py-2 hover:opacity-90 transition-all flex items-center" style="background-color: #6c757d;">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                <span>Cancel</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/menu-item/edit.js') }}"></script>

    <script>
        "use strict";

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // File name
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        if (jQuery().summernote) {
            $(".summernote-simple").summernote({
                dialogsInBody: true,
                minHeight: 230,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['paragraph']]
                ]
            });
        }

        $(document).ready(function () {
            // Other Select2
            $('.select2:not(#tags)').select2();

            // Tags Select2
            $('#tags').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: 'Select or type tags',
                allowClear: true,
                width: '100%'
            });
        });

      
        let itemSelectedFiles = [];

        function getFileKey(file) {
            return `${file.name}_${file.size}_${file.lastModified}`;
        }

        function getExistingItemImagesCount() {
            return document.querySelectorAll('.item-existing-image:not(.marked-for-delete)').length;
        }

        function updateItemImageUI() {
            const total = getExistingItemImagesCount() + itemSelectedFiles.length;
            const countText = document.getElementById('item-image-count-text');
            if (countText) countText.textContent = `${total} ${total === 1 ? 'Image' : 'Images'}`;

            const emptyState = document.getElementById('item-images-empty');
            if (emptyState) {
                if (total === 0) emptyState.classList.remove('hidden');
                else emptyState.classList.add('hidden');
            }
        }

        function syncItemImageInput() {
            const input = document.getElementById('item-images');
            if (!input) return;
            const dataTransfer = new DataTransfer();
            itemSelectedFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        function renderNewItemImages() {
            const grid = document.getElementById('item-images-grid');
            if (!grid) return;

            grid.querySelectorAll('.item-new-image').forEach(el => el.remove());

            itemSelectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'item-new-image relative group rounded-xl overflow-hidden border border-indigo-200 bg-gray-50 aspect-square shadow-sm';
                    wrapper.innerHTML = `
                        <img src="${event.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        <div class="absolute top-1.5 left-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-600 text-white text-[9px] font-medium shadow-sm">New</span>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <button type="button" onclick="removeNewItemImage(${index})" class="w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow-lg transform hover:scale-110 transition-all" title="Remove image">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent px-2 pt-5 pb-1.5">
                            <p class="text-[9px] text-white truncate">${file.name}</p>
                        </div>
                    `;
                    grid.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }

        function addItemImages(files) {
            const validFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
            validFiles.forEach(file => {
                const fileKey = getFileKey(file);
                const alreadyExists = itemSelectedFiles.some(f => getFileKey(f) === fileKey);
                if (!alreadyExists) itemSelectedFiles.push(file);
            });
            syncItemImageInput();
            renderNewItemImages();
            updateItemImageUI();
        }

        function removeNewItemImage(index) {
            itemSelectedFiles.splice(index, 1);
            syncItemImageInput();
            renderNewItemImages();
            updateItemImageUI();
        }

        function removeExistingItemMedia(mediaId) {
            const imageElement = document.getElementById(`existing-item-${mediaId}`);
            if (!imageElement) return;

            if (!confirm('Are you sure you want to remove this image? It will be deleted on save.')) return;

            imageElement.classList.add('marked-for-delete');
            imageElement.style.display = 'none';

            if (!document.querySelector(`input[name="deleted_images[]"][value="${mediaId}"]`)) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_images[]';
                hiddenInput.value = mediaId;
                document.getElementById('menu-item-form').appendChild(hiddenInput);
            }
            updateItemImageUI();
        }


        // ============================================================
        // 2. COVER IMAGES MANAGER
        // ============================================================
        let coverSelectedFiles = [];

        function getExistingCoverImagesCount() {
            return document.querySelectorAll('.cover-existing-image:not(.marked-for-delete)').length;
        }

        function updateCoverImageUI() {
            const total = getExistingCoverImagesCount() + coverSelectedFiles.length;
            const countText = document.getElementById('cover-image-count-text');
            if (countText) countText.textContent = `${total} ${total === 1 ? 'Cover' : 'Covers'}`;

            const emptyState = document.getElementById('cover-images-empty');
            if (emptyState) {
                if (total === 0) emptyState.classList.remove('hidden');
                else emptyState.classList.add('hidden');
            }
        }

        function syncCoverImageInput() {
            const input = document.getElementById('cover-images');
            if (!input) return;
            const dataTransfer = new DataTransfer();
            coverSelectedFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        function renderNewCoverImages() {
            const grid = document.getElementById('cover-images-grid');
            if (!grid) return;

            grid.querySelectorAll('.cover-new-image').forEach(el => el.remove());

            coverSelectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'cover-new-image relative group rounded-xl overflow-hidden border border-indigo-200 bg-gray-50 aspect-square shadow-sm';
                    wrapper.innerHTML = `
                        <img src="${event.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        <div class="absolute top-1.5 left-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-600 text-white text-[9px] font-medium shadow-sm">New</span>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <button type="button" onclick="removeNewCoverImage(${index})" class="w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow-lg transform hover:scale-110 transition-all" title="Remove cover">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent px-2 pt-5 pb-1.5">
                            <p class="text-[9px] text-white truncate">${file.name}</p>
                        </div>
                    `;
                    grid.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }

        function addCoverImages(files) {
            const validFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
            validFiles.forEach(file => {
                const fileKey = getFileKey(file);
                const alreadyExists = coverSelectedFiles.some(f => getFileKey(f) === fileKey);
                if (!alreadyExists) coverSelectedFiles.push(file);
            });
            syncCoverImageInput();
            renderNewCoverImages();
            updateCoverImageUI();
        }

        function removeNewCoverImage(index) {
            coverSelectedFiles.splice(index, 1);
            syncCoverImageInput();
            renderNewCoverImages();
            updateCoverImageUI();
        }

        function removeExistingCoverMedia(mediaId) {
            const imageElement = document.getElementById(`existing-cover-${mediaId}`);
            if (!imageElement) return;

            if (!confirm('Are you sure you want to remove this cover? It will be deleted on save.')) return;

            imageElement.classList.add('marked-for-delete');
            imageElement.style.display = 'none';

            if (!document.querySelector(`input[name="deleted_cover_images[]"][value="${mediaId}"]`)) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_cover_images[]';
                hiddenInput.value = mediaId;
                document.getElementById('menu-item-form').appendChild(hiddenInput);
            }
            updateCoverImageUI();
        }


        // ============================================================
        // 3. INITIALIZE DRAG & DROP AND LISTENERS
        // ============================================================
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- Item Images Setup ---
            const itemInput = document.getElementById('item-images');
            const itemDropzone = document.getElementById('item-image-dropzone');
            
            if (itemInput) {
                itemInput.addEventListener('change', e => addItemImages(e.target.files));
            }
            if (itemDropzone) {
                itemDropzone.addEventListener('dragover', e => {
                    e.preventDefault(); e.stopPropagation();
                    itemDropzone.classList.add('border-indigo-500', 'bg-indigo-50');
                });
                itemDropzone.addEventListener('dragleave', e => {
                    e.preventDefault(); e.stopPropagation();
                    itemDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
                });
                itemDropzone.addEventListener('drop', e => {
                    e.preventDefault(); e.stopPropagation();
                    itemDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
                    if (e.dataTransfer && e.dataTransfer.files) addItemImages(e.dataTransfer.files);
                });
            }

            // --- Cover Images Setup ---
            const coverInput = document.getElementById('cover-images');
            const coverDropzone = document.getElementById('cover-image-dropzone');
            
            if (coverInput) {
                coverInput.addEventListener('change', e => addCoverImages(e.target.files));
            }
            if (coverDropzone) {
                coverDropzone.addEventListener('dragover', e => {
                    e.preventDefault(); e.stopPropagation();
                    coverDropzone.classList.add('border-indigo-500', 'bg-indigo-50');
                });
                coverDropzone.addEventListener('dragleave', e => {
                    e.preventDefault(); e.stopPropagation();
                    coverDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
                });
                coverDropzone.addEventListener('drop', e => {
                    e.preventDefault(); e.stopPropagation();
                    coverDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
                    if (e.dataTransfer && e.dataTransfer.files) addCoverImages(e.dataTransfer.files);
                });
            }

            // Initialize Counters
            updateItemImageUI();
            updateCoverImageUI();
        });
    </script>
@endpush