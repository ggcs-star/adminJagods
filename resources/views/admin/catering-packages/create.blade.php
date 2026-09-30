@extends('admin.app')

@section('content')

    <div class="p-6 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Create Catering Package
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Create a catering package with sections and selectable menu items.
                </p>
            </div>

            <a href="{{ route('admin.catering-packages.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium text-sm">
                <span class="text-lg">←</span> Back to Packages
            </a>
        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="text-red-600 font-bold text-lg">!</div>
                    <div>
                        <h4 class="font-semibold text-red-800">Please fix the following errors:</h4>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif


        {{-- Added enctype for Image Upload --}}
        <form action="{{ route('admin.catering-packages.store') }}" method="POST" id="catering-package-form" class="pb-28"
            enctype="multipart/form-data">
            @csrf

            {{-- PACKAGE BASIC DETAILS --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="font-semibold text-gray-800">Package Details</h4>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                        {{-- Package Name --}}
                        <div class="md:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="package-name" value="{{ old('name') }}"
                                placeholder="e.g. Silver Catering Package"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                required>
                        </div>

                        {{-- Slug --}}
                        <div class="md:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="package-slug" value="{{ old('slug') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                required>
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-12">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border">{{ old('description') }}</textarea>
                        </div>

                        {{-- Price --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" step="0.01" min="0" name="price"
                                    value="{{ old('price', '0.00') }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 pl-8 pr-3 border"
                                    required>
                            </div>
                        </div>

                        <div class="md:col-span-12 mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Package Images</label>

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl relative hover:bg-gray-50 transition-colors cursor-pointer"
                                onclick="document.getElementById('package-images').click()">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center mt-2">
                                        <span class="relative font-medium text-indigo-600 hover:text-indigo-500">
                                            Upload multiple images
                                        </span>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                                <input id="package-images" name="images[]" type="file" class="sr-only" multiple
                                    accept="image/*" onchange="handleImageSelection(event)">
                            </div>

                            {{-- Image Preview Container --}}
                            <div id="image-preview-container" class="flex flex-wrap gap-4 mt-4"></div>
                        </div>

                        {{-- Price Type --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Type <span
                                    class="text-red-500">*</span></label>
                            <select name="price_type"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                required>
                                <option value="per_person" @selected(old('price_type', 'per_person') === 'per_person')>Per Person</option>
                                <option value="fixed" @selected(old('price_type') === 'fixed')>Fixed Price</option>
                                <option value="per_tray" @selected(old('price_type') === 'per_tray')>Per Tray</option>
                            </select>
                        </div>

                        {{-- Min Guests --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Guests <span
                                    class="text-red-500">*</span></label>
                            <input type="number" min="1" name="min_guests" value="{{ old('min_guests', 1) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                required>
                        </div>

                        {{-- Max Guests --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Guests</label>
                            <input type="number" min="1" name="max_guests" value="{{ old('max_guests') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                placeholder="Unlimited">
                        </div>

                        {{-- Lead Time --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (Hours) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" min="0" name="lead_time_hours"
                                value="{{ old('lead_time_hours', 24) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border"
                                required>
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border">
                                <option value="1" @selected(old('status', 1) == 1)>Active</option>
                                <option value="0" @selected(old('status') === '0')>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTIONS --}}
            <div class="mb-6 relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Package Sections</h3>
                        <p class="text-sm text-gray-500">
                            Create sections like Starters, Main Course, Desserts, etc.
                        </p>
                    </div>

                    <button type="button" onclick="addSection()"
                        style="background-color: #dc2626 !important; color: white !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'"
                        onmouseout="this.style.backgroundColor='#dc2626'"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white rounded-lg text-sm font-medium shadow-sm transition-colors cursor-pointer">
                        <span class="text-lg leading-none">+</span>
                        Add Section
                    </button>
                </div>
                <div id="sections-container"></div>

                {{-- Empty State --}}
                <div id="sections-empty-state"
                    class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-10 text-center transition-all">
                    <div class="text-gray-400 text-4xl mb-3">+</div>
                    <h4 class="font-semibold text-gray-700">No sections added</h4>
                    <p class="text-sm text-gray-500 mt-1 mb-6">Add at least one section to configure your package.</p>

                    <button type="button" onclick="addSection()"
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors cursor-pointer relative z-20">
                        Add First Section
                    </button>
                </div>
            </div>

            {{-- FORM ACTIONS (Sticky Footer) --}}
            <div
                class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sm:static sm:bg-transparent sm:border-0 sm:shadow-none p-4 sm:p-0">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 max-w-7xl mx-auto sm:bg-white sm:p-4 sm:shadow-lg sm:rounded-xl sm:border sm:border-gray-200">
                    <a href="{{ route('admin.catering-packages.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 text-center transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="submit-button"
                        style="background-color: #dc2626 !important; color: white !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'"
                        onmouseout="this.style.backgroundColor='#dc2626'"
                        class="px-6 py-2.5 text-white rounded-lg font-medium shadow-sm transition-colors cursor-pointer">
                        Save Catering Package
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
        let slugManuallyChanged = false;

        // ==========================================
        // Image Upload Logic (Multiple Images)
        // ==========================================
        let selectedFiles = new DataTransfer(); 

        function handleImageSelection(event) {
            event.stopPropagation();
            const files = event.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            const fileInput = document.getElementById('package-images');

            Array.from(files).forEach((file) => {
                if (!file.type.match('image.*')) return;

                const uniqueId = file.name + '-' + file.lastModified;

                let isDuplicate = false;
                Array.from(selectedFiles.files).forEach(f => {
                    if ((f.name + '-' + f.lastModified) === uniqueId) isDuplicate = true;
                });

                if (!isDuplicate) {
                    selectedFiles.items.add(file);

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewHtml = `
                            <div class="relative w-24 h-24 group rounded-lg overflow-hidden border border-gray-200 shadow-sm" id="img-preview-${uniqueId.replace(/[^a-zA-Z0-9]/g, '')}">
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" onclick="removeImage('${uniqueId}')" class="bg-red-600 text-white p-1.5 rounded-full hover:bg-red-700" title="Remove Image">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewContainer.insertAdjacentHTML('beforeend', previewHtml);
                    };
                    reader.readAsDataURL(file);
                }
            });

            fileInput.files = selectedFiles.files;
        }

        function removeImage(uniqueId) {
            const fileInput = document.getElementById('package-images');
            const newFiles = new DataTransfer();

            Array.from(selectedFiles.files).forEach(file => {
                if ((file.name + '-' + file.lastModified) !== uniqueId) {
                    newFiles.items.add(file);
                }
            });

            selectedFiles = newFiles;
            fileInput.files = selectedFiles.files;

            const previewId = `img-preview-${uniqueId.replace(/[^a-zA-Z0-9]/g, '')}`;
            const previewElement = document.getElementById(previewId);
            if (previewElement) {
                previewElement.remove();
            }
        }


        // ==========================================
        // Existing Logic
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
            if (container.children.length === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Modified addSection to include selection_type
        function addSection(data = null) {
            const index = sectionIndex++;
            const section = data || {
                name: '',
                description: '',
                selection_type: 'fixed',
                min_selections: 1,
                max_selections: 1,
                sort_order: index + 1,
                status: 1,
                items: []
            };

            const container = document.getElementById('sections-container');
            const sectionHtml = `
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-5 overflow-hidden" id="section-${index}">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 section-heading">${section.name || 'New Section'}</h4>
                    </div>
                    <button type="button" onclick="removeSection(${index})" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 cursor-pointer">
                        Remove
                    </button>
                </div>
                
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-6">
                        
                        {{-- Section Name --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section Name <span class="text-red-500">*</span></label>
                            <input type="text" name="sections[${index}][name]" value="${escapeHtml(section.name || '')}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border" placeholder="e.g. Starters" oninput="updateSectionHeading(${index}, this.value)" required>
                        </div>

                        {{-- Section Cover Image --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section Image</label>
                            <input type="file" name="sections[${index}][image]" accept="image/*" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-1.5 px-3 border bg-white cursor-pointer">
                        </div>

                        {{-- Section Status --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="sections[${index}][status]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border">
                                <option value="1" ${section.status == 1 ? 'selected' : ''}>Active</option>
                                <option value="0" ${section.status == 0 ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>

                        {{-- Section Description --}}
                        <div class="md:col-span-12">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="sections[${index}][description]" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border" placeholder="Brief description for this section...">${escapeHtml(section.description || '')}</textarea>
                        </div>

                        {{-- Selection Type --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selection Type <span class="text-red-500">*</span></label>
                            <select name="sections[${index}][selection_type]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border" required>
                                <option value="fixed" ${section.selection_type === 'fixed' ? 'selected' : ''}>Fixed</option>
                                <option value="custom" ${section.selection_type === 'custom' ? 'selected' : ''}>Custom</option>
                            </select>
                        </div>

                        {{-- Min/Max Selection --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Selection <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="sections[${index}][min_selections]" value="${section.min_selections ?? 1}" class="section-min w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Selection <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="sections[${index}][max_selections]" value="${section.max_selections ?? 1}" class="section-max w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-3 border" required>
                        </div>
                    </div>
                    
                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h5 class="text-sm font-semibold text-gray-800">Menu Items</h5>

                            <button type="button"
                                onclick="addItem(${index})"
                                style="background-color: #dc2626 !important; color: white !important;"
                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                onmouseout="this.style.backgroundColor='#dc2626'"
                                class="px-3 py-1.5 text-white rounded-lg text-xs font-medium cursor-pointer">
                                + Add Item
                            </button>
                        </div>
                        <div class="overflow-x-auto p-3">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-xs uppercase text-gray-500 border-b">
                                        <th class="text-left py-2 px-2">Menu Item</th>
                                        <th class="text-left py-2 px-2 w-32">Extra Price (₹)</th>
                                        <th class="text-center py-2 px-2 w-24">Default?</th>
                                        <th class="text-right py-2 px-2 w-20">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container-${index}" class="divide-y divide-gray-100"></tbody>
                            </table>
                            <div id="items-empty-${index}" class="text-center text-sm text-gray-400 py-4 hidden">No items added.</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', sectionHtml);

            if (section.items && section.items.length) {
                section.items.forEach(item => addItem(index, item));
            } else {
                addItem(index);
            }
            updateEmptyState();
        }

        // Remove Section
        function removeSection(index) {
            const section = document.getElementById(`section-${index}`);
            if (section && confirm('Are you sure you want to remove this section?')) {
                section.remove();
                updateEmptyState();
            }
        }

        // Modified Add Menu Item to include is_default
        function addItem(secIndex, item = null) {
            const container = document.getElementById(`items-container-${secIndex}`);
            const itemIndex = Date.now() + Math.floor(Math.random() * 1000);
            const optionsHtml = document.getElementById('menu-items-template').innerHTML;
            
            // Check state for is_default checkbox
            const isChecked = (item?.is_default == 1 || item?.is_default === '1') ? 'checked' : '';

            const itemHtml = `
            <tr id="item-${secIndex}-${itemIndex}">
                <td class="py-2 px-2">
                    <select name="sections[${secIndex}][items][${itemIndex}][menu_item_id]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-2 border bg-white" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td class="py-2 px-2">
                    <input type="number" step="0.01" min="0" name="sections[${secIndex}][items][${itemIndex}][extra_price]" value="${item?.extra_price ?? 0}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 text-sm py-2 px-2 border">
                </td>
                <td class="py-2 px-2 text-center">
                    <input type="checkbox" name="sections[${secIndex}][items][${itemIndex}][is_default]" value="1" ${isChecked} class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                </td>
                <td class="py-2 px-2 text-right">
                    <button type="button" onclick="removeItem(${secIndex}, '${itemIndex}')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg border border-red-200 cursor-pointer" title="Remove">×</button>
                    <input type="hidden" name="sections[${secIndex}][items][${itemIndex}][status]" value="1">
                </td>
            </tr>
        `;
            container.insertAdjacentHTML('beforeend', itemHtml);

            if (item?.menu_item_id) {
                const row = document.getElementById(`item-${secIndex}-${itemIndex}`);
                row.querySelector('select').value = item.menu_item_id;
            }

            document.getElementById(`items-empty-${secIndex}`).style.display = 'none';
        }

        // Remove Menu Item
        function removeItem(secIndex, itemIndex) {
            document.getElementById(`item-${secIndex}-${itemIndex}`).remove();
            const container = document.getElementById(`items-container-${secIndex}`);
            if (container.children.length === 0) {
                document.getElementById(`items-empty-${secIndex}`).style.display = 'block';
            }
        }

        // Update Section Heading Live
        function updateSectionHeading(index, value) {
            const section = document.getElementById(`section-${index}`);
            if (section) section.querySelector('.section-heading').textContent = value.trim() || 'New Section';
        }

        // Security HTML Escape
        function escapeHtml(value) {
            if (!value) return '';
            return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // Form Submission Validation
        document.getElementById('catering-package-form').addEventListener('submit', function(event) {
            const sections = document.querySelectorAll('#sections-container > div');
            if (sections.length === 0) {
                event.preventDefault();
                alert('Please add at least one package section.');
                return;
            }
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Saving...';
        });

        // Load Old Data / Empty State on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            @if (old('sections'))
                const oldSections = @json(old('sections'));
                Object.values(oldSections).forEach(section => {
                    addSection({
                        name: section.name ?? '',
                        description: section.description ?? '',
                        status: section.status ?? 1,
                        selection_type: section.selection_type ?? 'fixed',
                        min_selections: section.min_selections ?? 1,
                        max_selections: section.max_selections ?? 1,
                        items: Object.values(section.items ?? [])
                    });
                });
            @endif
            updateEmptyState();
        });
    </script>
@endpush