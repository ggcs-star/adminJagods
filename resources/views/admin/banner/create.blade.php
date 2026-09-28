@extends('admin.app')

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('banners/add') }}
            </div>
        </div>

        <div class="col-12">
            <div class="db-card">

                <div class="db-card-header">
                    <h3 class="db-card-title">
                        {{ __('banner.banners') }}
                    </h3>
                </div>

                <div class="db-card-body">

                    <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Target Type --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title required">
                                    Target Type
                                </label>

                                <div class="db-field-down-arrow">

                                    <select name="target_type" id="target_type"
                                        class="db-field-control appearance-none @error('target_type') invalid red-border @enderror">

                                        <option value="">
                                            Select Target Type
                                        </option>

                                        <option value="restaurant"
                                            {{ old('target_type') == 'restaurant' ? 'selected' : '' }}>
                                            Restaurant
                                        </option>

                                        <option value="category" {{ old('target_type') == 'category' ? 'selected' : '' }}>
                                            Category
                                        </option>

                                    </select>

                                </div>

                                @error('target_type')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- Target --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title required">
                                    Target
                                </label>

                                <div class="db-field-down-arrow">

                                    <select name="target_id" id="target_id"
                                        class="db-field-control appearance-none @error('target_id') invalid red-border @enderror">

                                        <option value="">
                                            Select Target
                                        </option>

                                    </select>

                                </div>

                                @error('target_id')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>
                            {{-- Show On Landing --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title required">
                                    Show On Landing
                                </label>

                                <div class="db-field-down-arrow">

                                    <select name="show_on_landing"
                                        class="db-field-control appearance-none @error('show_on_landing') invalid @enderror">
                                        <option value="0" {{ old('show_on_landing', 0) == 0 ? 'selected' : '' }}>
                                            No
                                        </option>

                                        <option value="1" {{ old('show_on_landing') == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>

                                </div>

                                @error('show_on_landing')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            {{-- Title --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title">
                                    {{ __('levels.title') }}
                                </label>

                                <input type="text" name="name"
                                    class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">

                                @error('name')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- URL --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title">
                                    {{ __('levels.url') }}
                                </label>

                                <input type="text" name="url"
                                    class="db-field-control @error('url') invalid @enderror" value="{{ old('url') }}">

                                @error('url')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- Status --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title required">
                                    {{ __('levels.status') }}
                                </label>

                                <div class="db-field-down-arrow">

                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">

                                        @foreach (trans('statuses') as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                                @error('status')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title">
                                    {{ __('levels.description') }}
                                </label>

                                <input type="text" name="description"
                                    class="db-field-control @error('description') invalid @enderror"
                                    value="{{ old('description') }}">

                                @error('description')
                                    <small class="db-field-alert">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- Image --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">

                                <label class="db-field-title required" for="customFile">
                                    {{ __('levels.image') }}
                                </label>

                                <input name="image" type="file"
                                    class="db-field-control @error('image') invalid @enderror" id="customFile"
                                    onchange="readURL(this);">

                                @if ($errors->has('image'))
                                    <small class="db-field-alert">
                                        {{ $errors->first('image') }}
                                    </small>
                                @endif

                            </div>


                            {{-- Submit --}}
                            <div class="form-col-12">

                                <button class="db-btn text-white bg-primary" type="submit">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('levels.submit') }}</span>
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
    <script>
        const restaurants = @json($restaurants ?? []);
        const categories = @json($categories ?? []);

        const oldTargetType = @json(old('target_type'));
        const oldTargetId = @json(old('target_id'));
    </script>

    <script src="{{ asset('js/banner/create.js') }}"></script>
@endpush
