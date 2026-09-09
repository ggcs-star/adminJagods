@extends('admin.app')

@push('css')
    <!-- Select2 CSS for Display Module multi-select -->
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('categories/add') }}
            </div>
        </div>

        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('restaurant.categories') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            {{-- Name --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.name') }}</label>
                                <input type="text" name="name"
                                    class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">

                                @error('name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Parent Category --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">Parent Category</label>
                                <div class="db-field-down-arrow">
                                    <select name="parent_id" class="db-field-control appearance-none @error('parent_id') invalid @enderror">
                                        <option value="">Main Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('parent_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Category Group --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="category_group_id">Category Group</label>
                                <div class="db-field-down-arrow">
                                    <select name="category_group_id" id="category_group_id"
                                        class="db-field-control appearance-none @error('category_group_id') invalid @enderror">
                                        <option value="">Select Category Group</option>
                                        @foreach ($categoryGroups as $categoryGroup)
                                            <option value="{{ $categoryGroup->id }}" {{ old('category_group_id') == $categoryGroup->id ? 'selected' : '' }}>
                                                {{ $categoryGroup->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_group_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Module --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="module_id">Module</label>
                                <div class="db-field-down-arrow">
                                    <select name="module_id" id="module_id"
                                        class="db-field-control appearance-none @error('module_id') invalid @enderror">
                                        <option value="">Select Module</option>
                                        @if (!blank($modules))
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
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

                            {{-- Sort Order --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" min="0"
                                    class="db-field-control @error('sort_order') invalid @enderror"
                                    value="{{ old('sort_order', 0) }}">
                                @error('sort_order')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status --}}
                            @if (auth()->user()->myrole == 1)
                                <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                    <label class="db-field-title required">{{ __('levels.status') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
                                            <option value="">---</option>
                                            @foreach (trans('statuses') as $key => $status)
                                                <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            {{-- Display Module (Multi-select) --}}
                            <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                <label class="db-field-title" for="display_module_id">{{ __('levels.display_module') ?? 'Display Module' }}</label>
                                <div class="db-field-down-arrow">
                                    @php
                                        // Default fallback logic
                                        $selectedModules = old('display_module_id', []);
                                        if (blank($selectedModules) && old('module_id')) {
                                            $selectedModules = [old('module_id')];
                                        }
                                    @endphp
                                    <select name="display_module_id[]" id="display_module_id"
                                        class="db-field-control select2 appearance-none @error('display_module_id') invalid @enderror"
                                        multiple="multiple">
                                        <option value="">---</option>
                                        @if (!blank($modules))
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}" {{ in_array($module->id, (array)$selectedModules) ? 'selected' : '' }}>
                                                    {{ $module->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('display_module_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Category Image --}}
                            <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                <label class="db-field-title" for="customFile">{{ __('restaurant.category_image') }}</label>
                                <input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror">
                                @if ($errors->has('image'))
                                    <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                @endif
                            </div>

                            {{-- Description --}}
                            <div class="form-col-12">
                                <label class="db-field-title">{{ __('levels.description') }}</label>
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
    <!-- Select2 JS -->
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <script>
        // CKEditor Initialization
        ClassicEditor
            .create(document.querySelector('#editor'), {
                editorContainer: {
                    height: '500px',
                    width: '100%',
                }
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = "130px";
                editor.ui.view.editable.element.style.overflow = "auto";
            })
            .catch(error => {
                console.error(error);
            });

        $(document).ready(function() {
            if($('.select2').length) {
                $('.select2').select2({
                    placeholder: "---"
                });
            }
        });
    </script>
@endpush