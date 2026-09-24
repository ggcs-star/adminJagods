@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('menu-items/add') }}
            </div>
        </div>

        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('restaurant.menu_item') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            {{-- Restaurant --}}
                            @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                                <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                    <label class="db-field-title required"
                                        for="restaurant_id">{{ __('levels.restaurant') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="restaurant_id"
                                            class="db-field-control appearance-none select2 custom-select2 @error('restaurant_id') invalid @enderror">
                                            <option value="">---</option>
                                            @if (!blank($restaurants))
                                                @foreach ($restaurants as $restaurant)
                                                    <option value="{{ $restaurant->id }}"
                                                        {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
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

                            {{-- Name --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
                                <input type="text" name="name" id="name"
                                    class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Module --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title required" for="module_id">Module</label>
                                <div class="db-field-down-arrow">
                                    <select name="module_id" id="module_id"
                                        class="db-field-control select2 custom-select2 @error('module_id') invalid @enderror"
                                        required>
                                        <option value="">Select Module</option>
                                        @foreach ($modules as $id => $slug)
                                            <option value="{{ $id }}"
                                                {{ old('module_id') == $id ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $slug)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('module_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Categories --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="categories">{{ __('restaurant.categories') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="categories[]" id="categories"
                                        class="db-field-control appearance-none select2 custom-select2 @error('categories') invalid @enderror"
                                        multiple="multiple">
                                        @if (!blank($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('categories')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Unit Price --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title required"
                                    for="unit_price">{{ __('levels.unit_price') }}</label>
                                <input type="text" name="unit_price" id="unit_price"
                                    class="db-field-control @error('unit_price') invalid @enderror"
                                    value="{{ old('unit_price') }}">
                                @error('unit_price')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Discount Price --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title"
                                    for="discount_price">{{ __('levels.discount_price') }}</label>
                                <input type="text" name="discount_price" id="discount_price"
                                    class="db-field-control @error('discount_price') invalid @enderror"
                                    value="{{ old('discount_price') }}">
                                @error('discount_price')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Item Type --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="restroType">Item Type</label>
                                <div class="db-field-down-arrow">
                                    <select name="restroType" id="restroType"
                                        class="db-field-control appearance-none @error('restroType') invalid @enderror">
                                        <option value="">Select Type</option>
                                        <option value="veg" {{ old('restroType') == 'veg' ? 'selected' : '' }}>Veg
                                        </option>
                                        <option value="non-veg" {{ old('restroType') == 'non-veg' ? 'selected' : '' }}>
                                            Non-Veg</option>
                                    </select>
                                </div>
                                @error('restroType')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Max Cart Quantity --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="max_cart_quantity">Max Cart Quantity</label>
                                <input type="number" min="1" name="max_cart_quantity" id="max_cart_quantity"
                                    class="db-field-control @error('max_cart_quantity') invalid @enderror"
                                    value="{{ old('max_cart_quantity', 10) }}">
                                @error('max_cart_quantity')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title required">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">
                                        <option value="">---</option>
                                        @foreach (trans('statuses') as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('status')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Multiple Images --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="customFile">
                                    {{ __('levels.image') }}
                                </label>

                                <div id="imageUploadBox"
                                    class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-primary transition bg-gray-50">
                                    <input type="file" name="images[]" id="customFile" multiple
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div id="uploadPlaceholder" class="pointer-events-none">
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
                                <div id="imageCount" class="hidden mt-3 text-sm font-medium text-heading"></div>

                                {{-- Preview --}}
                                <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4"></div>

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

                                    const input = document.getElementById('customFile');
                                    const preview = document.getElementById('imagePreview');
                                    const imageCount = document.getElementById('imageCount');
                                    const uploadBox = document.getElementById('imageUploadBox');

                                    if (!input) {
                                        return;
                                    }

                                    let selectedFiles = [];

                                    /*
                                    |--------------------------------------------------------------------------
                                    | File Input Change
                                    |--------------------------------------------------------------------------
                                    */

                                    input.addEventListener('change', function(event) {

                                        const files = Array.from(event.target.files);

                                        selectedFiles = [
                                            ...selectedFiles,
                                            ...files
                                        ];

                                        renderPreviews();

                                        updateInputFiles();
                                    });

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Render Preview
                                    |--------------------------------------------------------------------------
                                    */

                                    function renderPreviews() {

                                        preview.innerHTML = '';

                                        if (selectedFiles.length === 0) {

                                            imageCount.classList.add('hidden');

                                            return;
                                        }

                                        imageCount.classList.remove('hidden');

                                        imageCount.innerHTML = `
            <i class="fa-solid fa-images mr-1"></i>
            ${selectedFiles.length} image(s) selected
        `;

                                        selectedFiles.forEach(function(file, index) {

                                            if (!file.type.startsWith('image/')) {
                                                return;
                                            }

                                            const reader = new FileReader();

                                            reader.onload = function(event) {

                                                const wrapper = document.createElement('div');

                                                wrapper.className =
                                                    'relative group rounded-lg overflow-hidden border border-gray-200 bg-white';

                                                wrapper.innerHTML = `
                    <img
                        src="${event.target.result}"
                        alt="Preview"
                        class="w-full h-32 object-cover"
                    >

                    <button
                        type="button"
                        data-index="${index}"
                        class="remove-image absolute top-2 right-2 w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-20"
                        title="Remove image"
                    >
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>

                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                        <p class="text-white text-xs truncate">
                            ${file.name}
                        </p>
                    </div>
                `;

                                                preview.appendChild(wrapper);
                                            };

                                            reader.readAsDataURL(file);
                                        });
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Remove Image
                                    |--------------------------------------------------------------------------
                                    */

                                    preview.addEventListener('click', function(event) {

                                        const button =
                                            event.target.closest('.remove-image');

                                        if (!button) {
                                            return;
                                        }

                                        const index =
                                            parseInt(button.dataset.index, 10);

                                        selectedFiles.splice(index, 1);

                                        updateInputFiles();

                                        renderPreviews();
                                    });

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Update Input Files
                                    |--------------------------------------------------------------------------
                                    */

                                    function updateInputFiles() {

                                        const dataTransfer = new DataTransfer();

                                        selectedFiles.forEach(function(file) {
                                            dataTransfer.items.add(file);
                                        });

                                        input.files = dataTransfer.files;
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Drag & Drop
                                    |--------------------------------------------------------------------------
                                    */

                                    uploadBox.addEventListener('dragover', function(event) {

                                        event.preventDefault();

                                        uploadBox.classList.add(
                                            'border-primary',
                                            'bg-primary/5'
                                        );
                                    });

                                    uploadBox.addEventListener('dragleave', function() {

                                        uploadBox.classList.remove(
                                            'border-primary',
                                            'bg-primary/5'
                                        );
                                    });

                                    uploadBox.addEventListener('drop', function(event) {

                                        event.preventDefault();

                                        uploadBox.classList.remove(
                                            'border-primary',
                                            'bg-primary/5'
                                        );

                                        const files =
                                            Array.from(event.dataTransfer.files)
                                            .filter(function(file) {
                                                return file.type.startsWith('image/');
                                            });

                                        selectedFiles = [
                                            ...selectedFiles,
                                            ...files
                                        ];

                                        updateInputFiles();

                                        renderPreviews();
                                    });

                                });
                            </script>

                            @php
                                use App\Enums\MenuItemTag;
                            @endphp

                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="tags">
                                    Tags
                                </label>

                                <select name="tags[]" id="tags"
                                    class="db-field-control select2 custom-select2 @error('tags') invalid @enderror"
                                    multiple="multiple">
                                    @foreach (MenuItemTag::all() as $tag)
                                        <option value="{{ $tag }}"
                                            {{ in_array($tag, (array) old('tags', [])) ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('-', ' ', $tag)) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('tags')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="form-col-12">
                                <label class="db-field-title" for="description">{{ __('levels.description') }}</label>
                                <textarea name="description" class="db-field-control @error('description') invalid @enderror" id="editor"
                                    cols="30" rows="10">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-12 mt-4">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('levels.save') }}</span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/menu-item/create.js') }}"></script>
@endpush
