@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
@endpush

@php
    use App\Enums\RestroType;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('restaurant/edit') }}
            </div>
        </div>

        <div class="col-12">
            <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <div class="db-card">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_information') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
                                        <input type="text" name="name" id="name"
                                            class="db-field-control @error('name') invalid @enderror"
                                            value="{{ old('name', $restaurant->name) }}">

                                        @error('name')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="opening_time">{{ __('levels.opening_time') }}</label>
                                        <input type="time" name="opening_time" id="opening_time"
                                            class="db-field-control @error('opening_time') invalid @enderror"
                                            value="{{ old('opening_time', $restaurant->opening_time) }}">

                                        @error('opening_time')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="closing_time">{{ __('levels.closing_time') }}</label>
                                        <input type="time" name="closing_time" id="closing_time"
                                            class="db-field-control @error('closing_time') invalid @enderror"
                                            value="{{ old('closing_time', $restaurant->closing_time) }}">

                                        @error('closing_time')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="module">{{ __('levels.modules') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="module_id" id="module"
                                                class="db-field-control select2 appearance-none @error('module_id') invalid @enderror">
                                                <option value="">---</option>
                                                @if (!blank($modules))
                                                    @foreach ($modules as $module)
                                                        <option value="{{ $module->id }}" {{ old('module_id', $restaurant->module_id) == $module->id ? 'selected' : '' }}>
                                                            {{ $module->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('module_id')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="cuisines">{{ __('levels.cuisines') }}</label>
                                        <div class="db-field-down-arrow">
                                            @php
                                                $selectedCuisines = old('cuisines', $restaurant->cuisines->pluck('id')->toArray());
                                            @endphp
                                            <select name="cuisines[]" id="cuisines"
                                                class="db-field-control select2 appearance-none @error('cuisines') invalid @enderror"
                                                multiple="multiple">
                                                <option value="">---</option>
                                                @if (!blank($cuisines))
                                                    @foreach ($cuisines as $cuisine)
                                                        <option value="{{ $cuisine->id }}" {{ in_array($cuisine->id, $selectedCuisines) ? 'selected' : '' }}>
                                                            {{ $cuisine->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('cuisines')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12">
                                        <label class="db-field-title required" for="restroType">Restaurant Type</label>
                                        <div class="db-field-down-arrow">
                                            <select name="restroType" id="restroType"
                                                class="db-field-control @error('restroType') invalid @enderror">
                                                <option value="">---</option>
                                                <option value="{{ RestroType::VEG }}" {{ old('restroType', $restaurant->restroType) == RestroType::VEG ? 'selected' : '' }}>Veg</option>
                                                <option value="{{ RestroType::NON_VEG }}" {{ old('restroType', $restaurant->restroType) == RestroType::NON_VEG ? 'selected' : '' }}>Non-Veg</option>
                                                <option value="{{ RestroType::VEG_AND_NON_VEG }}" {{ old('restroType', $restaurant->restroType) == RestroType::VEG_AND_NON_VEG ? 'selected' : '' }}>Veg & Non-Veg</option>
                                            </select>
                                        </div>
                                        @error('restroType')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12">
                                        <label class="db-field-title required" for="restaurantaddress">{{ __('levels.restaurant_address') }}</label>
                                        <input type="text" name="restaurantaddress"
                                            class="db-field-control @error('restaurantaddress') invalid @enderror"
                                            id="restaurantaddress" value="{{ old('restaurantaddress', $restaurant->restaurantaddress) }}">
                                        @error('restaurantaddress')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12">
                                        <label class="db-field-title" for="description">{{ __('levels.description') }}</label>
                                        <textarea name="description" class="db-field-control @error('description') invalid @enderror" style="height: 5rem"
                                            id="editor">{{ old('description', $restaurant->description) }}</textarea>
                                        @error('description')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="customFile">{{ __('restaurant.logo') }}</label>
                                        <input type="file" name="restaurant_logo" id="customFile" class="db-field-control @error('restaurant_logo') invalid @enderror">
                                        @if ($errors->has('restaurant_logo'))
                                            <small class="db-field-alert">{{ $errors->first('restaurant_logo') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="customFile">{{ __('restaurant.background_image') }}</label>
                                        <input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror">
                                        @if ($errors->has('image'))
                                            <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="db-card mt-5">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_status') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.delivery') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="delivery_status"
                                                class="db-field-control appearance-none @error('delivery_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('delivery_statuses') as $delivery_statusKey => $delivery_status)
                                                    <option value="{{ $delivery_statusKey }}" {{ old('delivery_status', $restaurant->delivery_status) == $delivery_statusKey ? 'selected' : '' }}>
                                                        {{ $delivery_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('delivery_status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.pickup') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="pickup_status"
                                                class="db-field-control appearance-none @error('pickup_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('pickup_statuses') as $pickup_statusKey => $pickup_status)
                                                    <option value="{{ $pickup_statusKey }}" {{ old('pickup_status', $restaurant->pickup_status) == $pickup_statusKey ? 'selected' : '' }}>
                                                        {{ $pickup_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('pickup_status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.table') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="table_status"
                                                class="db-field-control appearance-none @error('table_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('table_statuses') as $table_statusKey => $table_status)
                                                    <option value="{{ $table_statusKey }}" {{ old('table_status', $restaurant->table_status) == $table_statusKey ? 'selected' : '' }}>
                                                        {{ $table_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('table_status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.current_status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="current_status"
                                                class="db-field-control appearance-none @error('current_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('current_statuses') as $current_statusKey => $current_status)
                                                    <option value="{{ $current_statusKey }}" {{ old('current_status', $restaurant->current_status) == $current_statusKey ? 'selected' : '' }}>
                                                        {{ $current_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('current_status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="status"
                                                class="db-field-control appearance-none @error('status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('statuses') as $statusKey => $status)
                                                    <option value="{{ $statusKey }}" {{ old('status', $restaurant->status) == $statusKey ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.waiter_status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="waiter_status"
                                                class="db-field-control appearance-none @error('waiter_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('waiter_statuses') as $waiter_statusKey => $waiter_status)
                                                    <option value="{{ $waiter_statusKey }}" {{ old('waiter_status', $restaurant->waiter_status) == $waiter_statusKey ? 'selected' : '' }}>
                                                        {{ $waiter_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('waiter_status')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12">
                                        <label class="db-field-title " for="sort_order">{{ __('levels.sort_order') }}</label>
                                        <input type="number" name="sort_order" class="db-field-control @error('sort_order') invalid @enderror"
                                            id="sort_order" value="{{ old('sort_order', $restaurant->sort_order) }}">
                                        @error('sort_order')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="db-btn text-white bg-primary">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>{{ __('levels.update') }}</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="db-card">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_location') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="lat">{{ __('levels.latitude') }}</label>
                                        <input type="text" name="lat" id="lat"
                                            class="db-field-control @error('lat') invalid @enderror"
                                            value="{{ old('lat', $restaurant->lat) }}">
                                        @error('lat')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="long">{{ __('levels.longitude') }}</label>
                                        <input type="text" name="long" id="long"
                                            class="db-field-control @error('long') invalid @enderror"
                                            value="{{ old('long', $restaurant->long) }}">
                                        @error('long')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <div id="googleMap"></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="db-card mt-5">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_owner_information') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="first_name">{{ __('levels.first_name') }}</label>
                                        <input type="text" name="first_name" id="first_name"
                                            class="db-field-control @error('first_name') invalid @enderror"
                                            value="{{ old('first_name', optional($restaurant->user)->first_name) }}">
                                        @error('first_name')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="last_name">{{ __('levels.last_name') }}</label>
                                        <input type="text" name="last_name" id="last_name"
                                            class="db-field-control @error('last_name') invalid @enderror"
                                            value="{{ old('last_name', optional($restaurant->user)->last_name) }}">
                                        @error('last_name')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="email">{{ __('levels.email') }}</label>
                                        <input type="text" name="email" id="email"
                                            class="db-field-control @error('email') invalid @enderror"
                                            value="{{ old('email', optional($restaurant->user)->email) }}">
                                        @error('email')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="username">{{ __('levels.username') }}</label>
                                        <input type="text" name="username" id="username"
                                            class="db-field-control @error('username') invalid @enderror"
                                            value="{{ old('username', optional($restaurant->user)->username) }}">
                                        @error('username')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="password">{{ __('levels.password') }}</label>
                                        <input type="password" name="password" id="password"
                                            class="db-field-control @error('password') invalid @enderror"
                                            placeholder="Leave blank to keep unchanged">
                                        @error('password')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="phone">{{ __('levels.phone') }}</label>
                                        <input type="text" name="phone" id="phone"
                                            class="db-field-control @error('phone') invalid @enderror"
                                            value="{{ old('phone', optional($restaurant->user)->phone) }}" onkeypress='validate(event)'>
                                        @error('phone')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title required" for="address">{{ __('levels.address') }}</label>
                                        <input type="text" name="address"
                                            class="db-field-control @error('address') invalid @enderror" id="address"
                                            value="{{ old('address', optional($restaurant->user)->address) }}">
                                        @error('address')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="deposit_amount">{{ __('levels.deposit_amount') }}</label>
                                        <input type="number" step="0.1" name="deposit_amount" id="deposit_amount"
                                            value="{{ old('deposit_amount', optional($restaurant->user)->deposit_amount) }}"
                                            class="db-field-control @error('deposit_amount') invalid @enderror">
                                        @error('deposit_amount')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="userstatus"
                                                class="db-field-control appearance-none @error('userstatus') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach (trans('user_statuses') as $key => $userstatus)
                                                    <option value="{{ $key }}" {{ old('userstatus', optional($restaurant->user)->status) == $key ? 'selected' : '' }}>
                                                        {{ $userstatus }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('userstatus')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&libraries=places&callback=initMap">
    </script>
    <!-- Isko 'create.js' ki jagah edit se relate karna hoga agar alag script hai to -->
    <script src="{{ asset('js/restaurant/create.js') }}"></script>
    <script src="{{ asset('js/phone_validation/index.js') }}"></script>
@endpush