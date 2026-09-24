@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('menu-items/edit') }}
            </div>
        </div>
        <button type="button" onclick="window.history.back();" class="db-btn text-white" style="background-color: #6c757d;">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back</span>
        </button>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('restaurant.menu_item') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                                <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                    <label class="db-field-title required"
                                        for="area">{{ __('levels.restaurant') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="restaurant_id" id="area"
                                            class="db-field-control !appearance-none select2 custom-select2 @error('restaurant_id') invalid @enderror">
                                            <option value="">---</option>
                                            @if (!blank($restaurants))
                                                @foreach ($restaurants as $restaurant)
                                                    <option value="{{ $restaurant->id }}"
                                                        {{ old('restaurant_id', $menuItem->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                                        {{ $restaurant->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    @error('restaurant_id')
                                        <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="restaurant_id"
                                    value="{{ auth()->user()->restaurant->id ?? 0 }}">
                            @endif

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
                                <input type="text" name="name" id="name"
                                    class="db-field-control @error('name') invalid @enderror"
                                    value="{{ old('name', $menuItem->name) }}">

                                @error('name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title required" for="module_id">
                                    Module
                                </label>

                                <select name="module_id" id="module_id" required>
                                    <option value="">Select Module</option>

                                    @foreach ($modules as $id => $slug)
                                        <option value="{{ $id }}"
                                            {{ old('module_id', $menuItem->module_id) == $id ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $slug)) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('module_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title" for="categories">{{ __('restaurant.categories') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="categories[]" id="categories"
                                        class="db-field-control appearance-none select2 custom-select2 @error('categories') invalid @enderror"
                                        multiple="multiple">
                                        @if (!blank($categories))
                                            @foreach ($categories as $category)
                                                @if (in_array($category->id, $menuItem_categories))
                                                    <option value="{{ $category->id }}" selected>{{ $category->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                @error('categories')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title required"
                                    for="unit_price">{{ __('levels.unit_price') }}</label>
                                <input type="text" name="unit_price" id="unit_price"
                                    class="db-field-control @error('unit_price') invalid @enderror"
                                    value="{{ old('unit_price', $menuItem->unit_price) }}">

                                @error('unit_price')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title"
                                    for="discount_price">{{ __('levels.discount_price') }}</label>
                                <input type="text" name="discount_price" id="discount_price"
                                    class="db-field-control @error('discount_price') invalid @enderror"
                                    value="{{ old('discount_price', $menuItem->discount_price) }}">

                                @error('discount_price')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title" for="restroType">
                                    Item Type
                                </label>

                                <select name="restroType" id="restroType"
                                    class="db-field-control @error('restroType') invalid @enderror">

                                    <option value="">Select Type</option>

                                    <option value="veg"
                                        {{ old('restroType', $menuItem->restroType ?? '') == 'veg' ? 'selected' : '' }}>
                                        Veg
                                    </option>

                                    <option value="non-veg"
                                        {{ old('restroType', $menuItem->restroType ?? '') == 'non-veg' ? 'selected' : '' }}>
                                        Non-Veg
                                    </option>
                                </select>

                                @error('restroType')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title" for="max_cart_quantity">
                                    Max Cart Quantity
                                </label>

                                <input type="number" min="1" name="max_cart_quantity" id="max_cart_quantity"
                                    class="db-field-control @error('max_cart_quantity') invalid @enderror"
                                    value="{{ old('max_cart_quantity', $menuItem->max_cart_quantity ?? '') }}">

                                @error('max_cart_quantity')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title required">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">
                                        <option value="">---</option>
                                        @foreach (trans('statuses') as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status', $menuItem->status) == $key ? 'selected' : '' }}>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('status')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Images --}}
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">

                                <label class="db-field-title">
                                    {{ __('levels.image') }}
                                </label>

                                {{-- Upload Box --}}
                                <div id="imageUploadBox"
                                    class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-primary transition bg-gray-50">

                                    <input type="file" name="images[]" id="customFile" multiple
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div class="pointer-events-none">

                                        <div class="flex justify-center mb-3">
                                            <div
                                                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                                                <i class="fa-solid fa-cloud-arrow-up text-primary text-xl"></i>
                                            </div>
                                        </div>

                                        <p class="text-sm font-medium text-heading">
                                            Click to upload images
                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">
                                            You can select multiple images
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            JPG, JPEG, PNG, WEBP — Max 4MB each
                                        </p>

                                    </div>
                                </div>


                                {{-- Image Count --}}
                                <div id="imageCount" class="mt-3 text-sm font-medium text-heading"></div>


                                {{-- Existing + New Images --}}
                                <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-4">

                                    {{-- Existing Images --}}
                                    @foreach ($menuItem->getMedia('menu-items') as $media)
                                        <div class="image-preview-item existing-image relative group rounded-lg overflow-hidden border border-gray-200 bg-white"
                                            data-media-id="{{ $media->id }}">

                                            <img src="{{ $media->getUrl() }}" alt="{{ $menuItem->name }}"
                                                class="w-full h-32 object-cover">

                                            {{-- Remove Existing Image Button --}}
                                            <button type="button"
                                                class="remove-existing-image absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center transition-transform hover:scale-110 z-20 shadow"
                                                data-media-id="{{ $media->id }}" title="Remove image">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>

                                            {{-- Existing Badge --}}
                                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                                                <p class="text-white text-xs truncate">
                                                    Existing Image
                                                </p>
                                            </div>

                                        </div>
                                    @endforeach

                                </div>


                                {{-- Deleted Existing Image IDs --}}
                                <div id="deletedImagesContainer"></div>


                                {{-- Validation --}}
                                @if ($errors->has('images'))
                                    <small class="db-field-alert">
                                        {{ $errors->first('images') }}
                                    </small>
                                @endif

                                @if ($errors->has('images.*'))
                                    <small class="db-field-alert">
                                        {{ $errors->first('images.*') }}
                                    </small>
                                @endif

                            </div>


                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    const input =
                                        document.getElementById('customFile');

                                    const preview =
                                        document.getElementById('imagePreview');

                                    const imageCount =
                                        document.getElementById('imageCount');

                                    const uploadBox =
                                        document.getElementById('imageUploadBox');

                                    const deletedImagesContainer =
                                        document.getElementById('deletedImagesContainer');


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Check Required Elements
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        !input ||
                                        !preview ||
                                        !imageCount ||
                                        !deletedImagesContainer
                                    ) {
                                        return;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | New Selected Files
                                    |--------------------------------------------------------------------------
                                    */

                                    let selectedFiles = [];


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Update Image Count
                                    |--------------------------------------------------------------------------
                                    */

                                    function updateImageCount() {

                                        const existingImages =
                                            preview.querySelectorAll(
                                                '.existing-image'
                                            ).length;

                                        const newImages =
                                            selectedFiles.length;

                                        const totalImages =
                                            existingImages + newImages;


                                        imageCount.innerHTML = `
                                            <i class="fa-solid fa-images mr-1"></i>
                                            ${totalImages} image(s)
                                        `;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Update File Input
                                    |--------------------------------------------------------------------------
                                    */

                                    function updateInputFiles() {

                                        const dataTransfer =
                                            new DataTransfer();


                                        selectedFiles.forEach(function(file) {

                                            dataTransfer.items.add(file);

                                        });


                                        input.files =
                                            dataTransfer.files;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Render New Image Previews
                                    |--------------------------------------------------------------------------
                                    */

                                    function renderNewImages() {

                                        /*
                                         * Sirf new images remove karo.
                                         *
                                         * Existing images ko touch nahi karna.
                                         */
                                        preview
                                            .querySelectorAll('.new-image')
                                            .forEach(function(element) {

                                                element.remove();

                                            });


                                        /*
                                         * New selected files render karo.
                                         */
                                        selectedFiles.forEach(function(file, index) {

                                            /*
                                             * Image file nahi hai to skip.
                                             */
                                            if (!file.type.startsWith('image/')) {
                                                return;
                                            }


                                            const reader =
                                                new FileReader();


                                            reader.onload = function(event) {

                                                const wrapper =
                                                    document.createElement('div');


                                                wrapper.className =
                                                    'image-preview-item new-image relative group rounded-lg overflow-hidden border border-gray-200 bg-white';


                                                wrapper.innerHTML = `
                                                    <img
                                                        src="${event.target.result}"
                                                        alt="Preview"
                                                        class="w-full h-32 object-cover"
                                                    >

                                                    <button
                                                        type="button"
                                                        class="remove-new-image absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center transition-transform hover:scale-110 z-20 shadow"
                                                        data-index="${index}"
                                                        title="Remove image"
                                                    >
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>

                                                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                                                        <p class="text-white text-xs truncate">
                                                            ${file.name}
                                                        </p>
                                                    </div>
                                                `;


                                                preview.appendChild(wrapper);


                                                updateImageCount();

                                            };


                                            reader.readAsDataURL(file);

                                        });

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Select New Images
                                    |--------------------------------------------------------------------------
                                    */

                                    input.addEventListener(
                                        'change',
                                        function(event) {

                                            const files =
                                                Array.from(
                                                    event.target.files
                                                ).filter(function(file) {

                                                    return file.type.startsWith(
                                                        'image/'
                                                    );

                                                });


                                            /*
                                             * Existing selected files + new files
                                             */
                                            selectedFiles = [
                                                ...selectedFiles,
                                                ...files
                                            ];


                                            /*
                                             * Input update
                                             */
                                            updateInputFiles();


                                            /*
                                             * Preview update
                                             */
                                            renderNewImages();


                                            /*
                                             * Count update
                                             */
                                            updateImageCount();

                                        }
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Remove Images
                                    |--------------------------------------------------------------------------
                                    */

                                    preview.addEventListener(
                                        'click',
                                        function(event) {


                                            /*
                                             * ----------------------------------------------------------
                                             * Existing Image Remove
                                             * ----------------------------------------------------------
                                             */

                                            const existingButton =
                                                event.target.closest(
                                                    '.remove-existing-image'
                                                );


                                            if (existingButton) {

                                                const mediaId =
                                                    existingButton.dataset.mediaId;


                                                /*
                                                 * Image wrapper find karo
                                                 */
                                                const imageWrapper =
                                                    existingButton.closest(
                                                        '.existing-image'
                                                    );


                                                /*
                                                 * UI se image remove karo
                                                 */
                                                if (imageWrapper) {

                                                    imageWrapper.remove();

                                                }


                                                /*
                                                 * Laravel ke liye hidden input
                                                 */
                                                const hiddenInput =
                                                    document.createElement(
                                                        'input'
                                                    );


                                                hiddenInput.type =
                                                    'hidden';

                                                hiddenInput.name =
                                                    'deleted_images[]';

                                                hiddenInput.value =
                                                    mediaId;


                                                deletedImagesContainer.appendChild(
                                                    hiddenInput
                                                );


                                                /*
                                                 * Count update
                                                 */
                                                updateImageCount();


                                                return;
                                            }


                                            /*
                                             * ----------------------------------------------------------
                                             * New Image Remove
                                             * ----------------------------------------------------------
                                             */

                                            const newButton =
                                                event.target.closest(
                                                    '.remove-new-image'
                                                );


                                            if (newButton) {

                                                const index =
                                                    parseInt(
                                                        newButton.dataset.index,
                                                        10
                                                    );


                                                /*
                                                 * selectedFiles se image remove
                                                 */
                                                selectedFiles.splice(
                                                    index,
                                                    1
                                                );


                                                /*
                                                 * Input update
                                                 */
                                                updateInputFiles();


                                                /*
                                                 * Preview update
                                                 */
                                                renderNewImages();


                                                /*
                                                 * Count update
                                                 */
                                                updateImageCount();


                                                return;
                                            }

                                        }
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Drag & Drop
                                    |--------------------------------------------------------------------------
                                    */

                                    if (uploadBox) {


                                        /*
                                         * Drag Over
                                         */
                                        uploadBox.addEventListener(
                                            'dragover',
                                            function(event) {

                                                event.preventDefault();


                                                uploadBox.classList.add(
                                                    'border-primary',
                                                    'bg-primary/5'
                                                );

                                            }
                                        );


                                        /*
                                         * Drag Leave
                                         */
                                        uploadBox.addEventListener(
                                            'dragleave',
                                            function() {

                                                uploadBox.classList.remove(
                                                    'border-primary',
                                                    'bg-primary/5'
                                                );

                                            }
                                        );


                                        /*
                                         * Drop
                                         */
                                        uploadBox.addEventListener(
                                            'drop',
                                            function(event) {

                                                event.preventDefault();


                                                uploadBox.classList.remove(
                                                    'border-primary',
                                                    'bg-primary/5'
                                                );


                                                const files =
                                                    Array.from(
                                                        event.dataTransfer.files
                                                    ).filter(function(file) {

                                                        return file.type.startsWith(
                                                            'image/'
                                                        );

                                                    });


                                                /*
                                                 * Add dropped files
                                                 */
                                                selectedFiles = [
                                                    ...selectedFiles,
                                                    ...files
                                                ];


                                                /*
                                                 * Input update
                                                 */
                                                updateInputFiles();


                                                /*
                                                 * Preview update
                                                 */
                                                renderNewImages();


                                                /*
                                                 * Count update
                                                 */
                                                updateImageCount();

                                            }
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Initial Image Count
                                    |--------------------------------------------------------------------------
                                    */

                                    updateImageCount();

                                });
                            </script>

                            @php
                                use App\Enums\MenuItemTag;

                                $selectedTags = old(
                                    'tags',
                                    is_array($menuItem->tags)
                                        ? $menuItem->tags
                                        : (is_string($menuItem->tags)
                                            ? json_decode($menuItem->tags, true)
                                            : []),
                                );
                            @endphp

                            {{-- Tags --}}
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label class="db-field-title" for="tags">
                                    Tags
                                </label>

                                <select name="tags[]" id="tags"
                                    class="db-field-control select2 custom-select2 @error('tags') invalid @enderror"
                                    multiple="multiple">
                                    @foreach (MenuItemTag::all() as $tag)
                                        <option value="{{ $tag }}"
                                            {{ in_array($tag, (array)$selectedTags) ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('-', ' ', $tag)) }}
                                        </option>
                                    @endforeach

                                    {{-- Custom tags jo database me hain lekin Enum me nahi hain --}}
                                    @foreach ((array) $selectedTags as $tag)
                                        @if (!in_array($tag, MenuItemTag::all()))
                                            <option value="{{ $tag }}" selected>
                                                {{ $tag }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

                                @error('tags')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-col-12">
                                <label class="db-field-title required"
                                    for="description">{{ __('levels.description') }}</label>
                                <textarea name="description" class="db-field-control @error('description') invalid @enderror" id="editor"
                                    cols="30" rows="10">{{ old('description', $menuItem->description) }}</textarea>
                                @error('description')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('levels.save') }}</span>
                                </button>
                                <button type="button" onclick="window.history.back();" class="db-btn text-white"
                                    style="background-color: #6c757d;">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    <span>Back</span>
                                </button>
                            </div>

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
@endpush