@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('menu-items/view') }}
            </div>
        </div>

        <div class="col-12">
            <div class="grid grid-cols-1 sm:grid-cols-5 mb-4 sm:mb-0">
                <button type="button" class="db-tabBtn active" data-tab="#information">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>{{ __('levels.coupon_info') }}</span>
                </button>
                <button type="button" class="db-tabBtn" data-tab="#image">
                    <i class="fa-solid fa-cube"></i>
                    <span>{{ __('levels.image') }}</span>
                </button>
            </div>
            <div class="db-tabDiv active" id="information">
                <ul class="db-list multiple">
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.name') }}</span>
                        <span class="db-list-item-text">{{ $menuItem->name }}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.status') }}</span>
                        <span class="db-list-item-text">{!! $menuItem->statusName !!}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.created_date') }}</span>
                        <span class="db-list-item-text">{{ $menuItem->created_at->diffForHumans() }}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.description') }}</span>
                        <span class="db-list-item-text">{{ strip_tags($menuItem->description) }}</span>
                    </li>

                </ul>
            </div>
            <div class="db-tabDiv" id="image">

                @php
                    $menuImages = $menuItem->getMedia('menu-items');
                @endphp

                @if ($menuImages->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                        @foreach ($menuImages as $media)
                            <div class="db-card p-3">

                                <div class="relative overflow-hidden rounded">

                                    <img class="d-block w-100 h-232 rounded object-cover" src="{{ $media->getUrl() }}"
                                        alt="{{ $menuItem->name }}">

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="db-card p-4 text-center">
                        <p class="text-sm text-gray-500">
                            {{ __('No images available') }}
                        </p>
                    </div>
                @endif

            </div>


        </div>

    </div>
@endsection
