@extends('admin.app')

@section('content')
    @php
        $currency      = config('app.currency', '₹');
        $fallbackImage = asset('images/default.png');
        $menuItemImage = $menuItem->getFirstMediaUrl('menu-items') ?: $fallbackImage;
    @endphp

    <div class="row">
        <div class="col-12 mb-3">
            <span class="text-gray-500 font-medium text-sm">Menu Items / Recommendations</span>
        </div>

        <div class="col-12">
            <div class="db-card">
                {{-- Header --}}
                <div class="db-card-header flex justify-between items-center bg-gray-50 border-b pb-3 mb-4 rounded-t-lg flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $menuItemImage }}"
                             alt="{{ $menuItem->name }}"
                             class="w-11 h-11 rounded-lg object-cover border border-gray-200"
                             onerror="this.src='{{ $fallbackImage }}'">
                        <div class="leading-tight">
                            <h3 class="db-card-title text-xl font-semibold">Link Recommended Items</h3>
                            <p class="text-primary font-medium text-sm">{{ $menuItem->name }}</p>
                        </div>
                    </div>

                    <button type="button" onclick="window.history.back();"
                            class="db-btn text-white rounded px-3 py-2 transition-all hover:opacity-90 flex items-center"
                            style="background-color: #6c757d;">
                        <i class="fa-solid fa-arrow-left mr-1"></i>
                        <span>Back</span>
                    </button>
                </div>

                <div class="db-card-body p-4">
                    @if ($totalMenuItems === 0)
                        {{-- Empty state --}}
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fa-solid fa-utensils text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-1">No other menu items available</h4>
                            <p class="text-sm text-gray-500 mb-4">Create more menu items first to link them as recommendations.</p>
                        </div>
                    @else
                        <form action="{{ route('admin.menu-items.sync-recommendations', $menuItem) }}"
                              method="POST" id="recommendations-form">
                            @csrf

                            @if ($errors->any())
                                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                                    @foreach ($errors->all() as $err)
                                        <div><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $err }}</div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Info banner --}}
                            <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-100 text-xs text-blue-700 flex items-start gap-2">
                                <i class="fa-solid fa-circle-info mt-0.5"></i>
                                <div>
                                    These items will appear as <strong>“You may also like”</strong> suggestions on the item's detail page.
                                    Choose up to <strong>50</strong> items.
                                    Total catalogue: <strong>{{ number_format($totalMenuItems) }}</strong> items.
                                </div>
                            </div>

                            {{-- Search & selection --}}
                            <div class="mb-5">
                                <label class="db-field-title mb-2 block" for="recommended_items">
                                    Recommended Items
                                    <span class="text-xs font-normal text-gray-500 ml-1">(type to search, click to add)</span>
                                </label>

                                <select name="recommended_items[]"
                                        id="recommended_items"
                                        class="db-field-control custom-select2"
                                        multiple="multiple"
                                        style="width: 100%;"
                                        aria-label="Select recommended items">
                                    {{-- Only pre-selected items in DOM (few rows) --}}
                                    @foreach ($recommended_items as $item)
                                        @php $imageUrl = $item->getFirstMediaUrl('menu-items') ?: $fallbackImage; @endphp
                                        <option value="{{ $item->id }}"
                                                data-image="{{ $imageUrl }}"
                                                data-price="{{ number_format($item->unit_price, 2) }}"
                                                selected>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Selected recommendations card --}}
                            <div class="mt-4">
                                <div class="border border-gray-200 rounded-xl bg-white overflow-hidden shadow-sm">
                                    {{-- Card header --}}
                                    <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex-wrap gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-primary/10">
                                                <i class="fa-solid fa-list-check text-primary text-xs"></i>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700">Selected recommendations</span>
                                            <span id="selected-count-badge"
                                                  class="inline-flex items-center justify-center min-w-[24px] h-6 px-2 rounded-full bg-primary/10 text-primary text-xs font-bold">
                                                0
                                            </span>
                                        </div>

                                        <button type="button" id="clear-all-btn"
                                                class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded-md transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-red-500"
                                                disabled>
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            <span>Clear All</span>
                                        </button>
                                    </div>

                                    {{-- Card body --}}
                                    <div id="selected-preview" class="max-h-[380px] overflow-y-auto p-3 space-y-2"></div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-wrap gap-3 mt-6 border-t pt-4">
                                <button type="submit" id="save-btn"
                                        class="db-btn text-white bg-primary rounded px-5 py-2 hover:opacity-90 transition-all flex items-center disabled:opacity-60 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-circle-check mr-2"></i>
                                    <span>Save Recommendations</span>
                                </button>
                                <button type="button" onclick="window.history.back();"
                                        class="db-btn text-gray-700 bg-gray-100 rounded px-5 py-2 hover:bg-gray-200 transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 4px 6px;
            min-height: 44px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            padding: 2px 8px;
            margin: 3px 4px 0 0;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #9ca3af;
            margin-right: 4px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ef4444;
            background: transparent;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: #eef2ff !important;
            color: #1f2937 !important;
        }
        .select2-results__option { padding: 6px 10px; }
        .select2-container--default .select2-results__option--disabled { opacity: .5; }

        /* Nice scrollbar for the selected preview box */
        #selected-preview::-webkit-scrollbar { width: 6px; }
        #selected-preview::-webkit-scrollbar-track { background: transparent; }
        #selected-preview::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 999px;
        }
        #selected-preview::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
@endpush

@push('js')
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            const $select = $('#recommended_items');
            if (!$select.length) return;

            const fallbackImage = @json($fallbackImage);
            const currency      = @json($currency);
            const searchUrl     = @json(route('admin.menu-items.search-recommendations', $menuItem));
            const maxAllowed    = 50;

            /* ---------- Meta helper (works for AJAX + pre-loaded options) ---------- */
            function getMeta(item) {
                const $el = item.element ? $(item.element) : null;
                return {
                    image: item.image || ($el ? $el.data('image') : null) || fallbackImage,
                    price: item.price || ($el ? $el.data('price') : null) || '0.00',
                };
            }

            function esc(str) {
                return $('<div>').text(str).html();
            }

            /* ---------- Templates ---------- */
            function formatMenuItemResult(item) {
                if (item.loading) return item.text;
                if (!item.id) return $('<span>').text(item.text);

                const { image, price } = getMeta(item);
                return $(`
                    <div class="flex items-center gap-3 py-1">
                        <img src="${image}"
                             class="w-10 h-10 rounded-md object-cover border border-gray-200 flex-shrink-0"
                             onerror="this.src='${fallbackImage}'">
                        <div class="flex flex-col leading-tight">
                            <span class="text-sm font-semibold text-gray-700">${esc(item.text)}</span>
                            <span class="text-xs text-gray-500">${currency}${price}</span>
                        </div>
                    </div>
                `);
            }

            function formatMenuItemSelection(item) {
                if (!item.id) return item.text;
                const { image, price } = getMeta(item);
                return $(`
                    <span class="inline-flex items-center gap-1.5">
                        <img src="${image}" class="w-5 h-5 rounded object-cover"
                             onerror="this.src='${fallbackImage}'">
                        <span class="text-xs text-gray-700">${esc(item.text)}</span>
                        <span class="text-[11px] text-red-500 font-medium">(${currency}${price})</span>
                    </span>
                `);
            }

            /* ---------- Preview box + count badge ---------- */
            function updateUI() {
                const data     = $select.select2('data') || [];
                const $preview = $('#selected-preview');
                const $badge   = $('#selected-count-badge');
                const $clear   = $('#clear-all-btn');

                $badge.text(data.length);
                $clear.prop('disabled', data.length === 0);
                $preview.empty();

                // Empty state
                if (!data.length) {
                    $preview.html(`
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-3">
                                <i class="fa-regular fa-bookmark text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600">No recommendations selected yet</p>
                            <p class="text-xs text-gray-400 mt-1">Search above and click items to add them here</p>
                        </div>
                    `);
                    return;
                }

                // Rows
                data.forEach((item, index) => {
                    if (!item.id) return;
                    const { image, price } = getMeta(item);

                    $preview.append(`
                        <div class="flex items-center gap-3 p-2 rounded-lg border border-gray-200 bg-white hover:border-primary/40 hover:shadow-sm transition"
                             data-id="${item.id}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-md bg-primary/10 text-primary text-xs font-bold flex-shrink-0">
                                ${index + 1}
                            </span>
                            <img src="${image}"
                                 class="w-10 h-10 rounded-md object-cover border border-gray-200 flex-shrink-0"
                                 onerror="this.src='${fallbackImage}'">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 truncate">${esc(item.text)}</p>
                                <p class="text-xs text-gray-500">${currency}${price}</p>
                            </div>
                            <button type="button" data-id="${item.id}"
                                    class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50 transition flex-shrink-0"
                                    aria-label="Remove ${esc(item.text)}">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    `);
                });
            }

            /* ---------- Init Select2 (AJAX) ---------- */
            if ($select.hasClass('select2-hidden-accessible')) $select.select2('destroy');

            $select.select2({
                placeholder: 'Search menu items by name…',
                allowClear: true,
                closeOnSelect: false,
                width: '100%',
                minimumInputLength: 0,
                templateResult: formatMenuItemResult,
                templateSelection: formatMenuItemSelection,
                escapeMarkup: m => m,
                language: {
                    inputTooShort: () => 'Start typing to search…',
                    noResults: () => 'No menu items match your search',
                    searching: () => 'Searching…',
                    loadingMore: () => 'Loading more results…',
                },
                ajax: {
                    url: searchUrl,
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: params => ({
                        q:    params.term || '',
                        page: params.page || 1,
                    }),
                    processResults: (data, params) => {
                        params.page = params.page || 1;
                        return {
                            results:    data.results,
                            pagination: { more: data.pagination.more },
                        };
                    },
                },
            });

            // Enforce max selection count
            $select.on('select2:selecting', function (e) {
                const current = ($select.val() || []).length;
                if (current >= maxAllowed) {
                    e.preventDefault();
                    alert(`You can only link up to ${maxAllowed} recommended items.`);
                }
            });

            $select.on('change', updateUI);
            updateUI();

            /* ---------- Row remove (click ✕ in the preview card) ---------- */
            $('#selected-preview').on('click', 'button[data-id]', function () {
                const id = String($(this).data('id'));
                const current = ($select.val() || []).filter(v => String(v) !== id);
                $select.val(current).trigger('change');
            });

            /* ---------- Clear all ---------- */
            $('#clear-all-btn').on('click', function () {
                if (!($select.val() || []).length) return;
                if (!confirm('Remove all selected recommendations?')) return;
                $select.val(null).trigger('change');
            });

            /* ---------- Submit loading state ---------- */
            $('#recommendations-form').on('submit', function (e) {
                const $btn = $('#save-btn');
                if ($btn.prop('disabled')) { e.preventDefault(); return; }
                $btn.prop('disabled', true);
                $btn.find('i').removeClass('fa-circle-check').addClass('fa-spinner fa-spin');
                $btn.find('span').text('Saving…');
            });

            /* ---------- Unsaved changes warning ---------- */
            let isDirty = false;
            $select.on('change', () => { isDirty = true; });
            $('#recommendations-form').on('submit', () => { isDirty = false; });
            window.addEventListener('beforeunload', e => {
                if (isDirty) { e.preventDefault(); e.returnValue = ''; }
            });
        });
    </script>
@endpush