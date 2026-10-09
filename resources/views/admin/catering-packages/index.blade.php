@extends('admin.app')

@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm mb-6 border border-gray-100">

            {{-- Header --}}
            <div
                class="p-5 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center bg-gray-50 rounded-t-lg">
                <h4 class="text-xl font-semibold text-gray-800">
                    Catering Packages
                </h4>

                <a href="{{ route('admin.catering-packages.create') }}"
                    style="background-color: #dc2626 !important; color: white !important;"
                    class="text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-sm flex items-center gap-2 transition hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Create New Package
                </a>
            </div>

            {{-- Filters --}}
            <div class="p-5">
                <form id="package-filter-form" action="{{ route('admin.catering-packages.index') }}" method="GET"
                    class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6">

                    {{-- Search Filter --}}
                    <div class="flex-1 max-w-md">
                        <input type="text" name="search" id="package-search" value="{{ request('search') }}"
                            placeholder="Search packages by name or slug..." autocomplete="off"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <select name="status" id="package-status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="1" @selected(request('status') === '1')>Active</option>
                            <option value="0" @selected(request('status') === '0')>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" style="background-color: #dc2626; color: #ffffff;"
                        class="hover:opacity-90 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Search
                    </button>

                    <a href="{{ route('admin.catering-packages.index') }}" id="reset-package-filters"
                        style="display: inline-flex; align-items: center; justify-content: center; position: relative; z-index: 10; cursor: pointer;"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </form>

                {{-- Table Results --}}
                <div id="packages-results" class="transition-opacity duration-200">
                    @include('admin.catering-packages.partials.results')
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $form = $('#package-filter-form');
            const $results = $('#packages-results');
            const formUrl = $form.attr('action');

            let searchTimer = null;
            let currentRequest = null;

            function loadPackages(url = formUrl, data = null, updateHistory = true) {
                if (currentRequest) {
                    currentRequest.abort();
                }

                $results.addClass('opacity-50');

                currentRequest = $.ajax({
                    url: url,
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $results.html(response.html);

                        // Update Browser URL without reloading
                        if (updateHistory) {
                            const stateUrl = new URL(url, window.location.origin);

                            if (data !== null) {
                                stateUrl.search = '';
                                const queryString = typeof data === 'string' ? data : $.param(data);
                                const params = new URLSearchParams(queryString);

                                params.forEach(function(value, key) {
                                    if (value !== '') {
                                        stateUrl.searchParams.set(key, value);
                                    }
                                });
                            }

                            window.history.pushState({}, '', stateUrl.pathname + stateUrl.search);
                        }
                    },
                    error: function(xhr, status) {
                        if (status !== 'abort') {
                            console.error('AJAX error:', xhr.responseText);
                            alert('Unable to load catering packages. Please try again.');
                        }
                    },
                    complete: function() {
                        $results.removeClass('opacity-50');
                        currentRequest = null;
                    }
                });
            }

            $form.on('submit', function(e) {
                e.preventDefault();
                clearTimeout(searchTimer);
                loadPackages(formUrl, $form.serialize());
            });

            $('#package-search').on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    loadPackages(formUrl, $form.serialize());
                }, 400);
            });

            $('#package-status').on('change', function() {
                loadPackages(formUrl, $form.serialize());
            });

            $(document).on('click', '#packages-results .pagination a', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                if (pageUrl) {
                    loadPackages(pageUrl);
                }
            });

            window.addEventListener('popstate', function() {
                loadPackages(window.location.pathname + window.location.search, null, false);
            });
        });
    </script>
@endsection
