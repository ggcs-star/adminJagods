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

                            {{-- Image --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
                                <label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>
                                <input type="file" name="image" id="customFile"
                                    class="db-field-control @error('image') invalid @enderror">
                                @if ($errors->has('image'))
                                    <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                @endif
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
