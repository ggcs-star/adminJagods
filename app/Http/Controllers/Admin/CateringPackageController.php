<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\CateringPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CateringPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = CateringPackage::query()
            ->withCount('sections');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $packages = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.catering-packages.index', compact('packages'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $menuItems = MenuItem::query()
            ->where('status', 5) // Updated to match active status 5
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.catering-packages.create', compact('menuItems'));
    }

    /**
     * Store catering package.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePackage($request);

        DB::transaction(function () use ($request, $validated) {
            $package = CateringPackage::create([
                'module_id' => $validated['module_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'price_type' => $validated['price_type'],
                'min_guests' => $validated['min_guests'],
                'max_guests' => $validated['max_guests'] ?? null,
                'lead_time_hours' => $validated['lead_time_hours'],
                'sort_order' => $validated['sort_order'] ?? 0, // Safe fallback
                'status' => $validated['status'],
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $package
                        ->addMedia($image)
                        ->toMediaCollection('catering_package_images');
                }
            }

            // Updated to pass $request object for section image handling
            $this->syncSections($package, $request);
        });

        return redirect()
            ->route('admin.catering-packages.index')
            ->withSuccess('Catering package created successfully.');
    }

    public function edit($id)
    {
        $package = CateringPackage::with([
            'sections.items.menuItem',
        ])->findOrFail($id);

        $menuItems = MenuItem::query()
            ->where('status', 5)
            ->orderBy('name', 'asc')
            ->get();

        return view(
            'admin.catering-packages.edit',
            compact('package', 'menuItems')
        );
    }

    /**
     * Update catering package.
     */
    public function update(Request $request, $id)
    {
        $package = CateringPackage::findOrFail($id);

        $validated = $this->validatePackage($request, $package->id);

        DB::transaction(function () use ($request, $validated, $package) {
            $package->update([
                'module_id' => $validated['module_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'price_type' => $validated['price_type'],
                'min_guests' => $validated['min_guests'],
                'max_guests' => $validated['max_guests'] ?? null,
                'lead_time_hours' => $validated['lead_time_hours'],
                'sort_order' => $validated['sort_order'] ?? 0, // Safe fallback
                'status' => $validated['status'],
            ]);

            // Updated to pass $request object for section image handling
            $this->syncSections($package, $request);
        });

        return redirect()
            ->route('admin.catering-packages.index')
            ->withSuccess('Catering package updated successfully.');
    }

    /**
     * Delete catering package.
     */
    public function destroy($id)
    {
        $package = CateringPackage::findOrFail($id);

        DB::transaction(function () use ($package) {
            $package->delete();
        });

        return redirect()
            ->route('admin.catering-packages.index')
            ->withSuccess('Catering package deleted successfully.');
    }

    /**
     * Validate package.
     */
    private function validatePackage(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'module_id' => [
                'nullable',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:catering_packages,slug,' . $id,
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'price_type' => [
                'required',
                'in:per_person,fixed,per_tray',
            ],
            'min_guests' => [
                'required',
                'integer',
                'min:1',
            ],
            'max_guests' => [
                'nullable',
                'integer',
                'gte:min_guests',
            ],
            'lead_time_hours' => [
                'required',
                'integer',
                'min:0',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'status' => [
                'required',
                'boolean',
            ],
            'sections' => [
                'nullable',
                'array',
            ],
            'sections.*.name' => [
                'required',
                'string',
                'max:255',
            ],
            'sections.*.description' => [
                'nullable',
                'string',
            ],
            // New Validation fields for Section Image & Selection Type
            'sections.*.image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'sections.*.selection_type' => [
                'required',
                'string',
                'in:fixed,custom',
            ],
            'sections.*.min_selections' => [
                'required',
                'integer',
                'min:0',
            ],
            'sections.*.max_selections' => [
                'required',
                'integer',
                'min:1',
            ],
            'sections.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'sections.*.status' => [
                'nullable',
                'boolean',
            ],
            'sections.*.items' => [
                'nullable',
                'array',
            ],
            'sections.*.items.*.menu_item_id' => [
                'required',
                'integer',
                'exists:menu_items,id',
            ],
            'sections.*.items.*.extra_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            // New validation for is_default
            'sections.*.items.*.is_default' => [
                'nullable',
                'boolean',
            ],
            'sections.*.items.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'sections.*.items.*.status' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    /**
     * Sync package sections and their items.
     */
    private function syncSections(CateringPackage $package, Request $request): void
    {
        $sections = $request->input('sections', []);

        $existingSectionIds = $package
            ->sections()
            ->pluck('id')
            ->toArray();

        $receivedSectionIds = [];

        foreach ($sections as $key => $sectionData) {
            $sectionId = $sectionData['id'] ?? null;
            
            $sectionDataToSave = [
                'name' => $sectionData['name'],
                'description' => $sectionData['description'] ?? null,
                'selection_type' => $sectionData['selection_type'] ?? 'fixed',
                'min_selections' => $sectionData['min_selections'],
                'max_selections' => $sectionData['max_selections'],
                'sort_order' => $sectionData['sort_order'] ?? 0,
                'status' => $sectionData['status'] ?? 1,
            ];

            /*
             * Update existing section
             */
            if ($sectionId && in_array($sectionId, $existingSectionIds)) {
                $section = $package
                    ->sections()
                    ->where('id', $sectionId)
                    ->first();

                $section->update($sectionDataToSave);
            }
            /*
             * Create new section
             */ 
            else {
                $section = $package->sections()->create($sectionDataToSave);
            }

            $receivedSectionIds[] = $section->id;

            /*
             * Section Image Upload Logic
             */
            if ($request->hasFile("sections.{$key}.image")) {
                // Pehle wali image hata denge taaki duplication na ho
                $section->clearMediaCollection('catering_section_images');
                
                $section->addMedia($request->file("sections.{$key}.image"))
                        ->toMediaCollection('catering_section_images');
            }

            /*
             * Validate min/max relationships
             */
            if ($section->min_selections > $section->max_selections) {
                throw ValidationException::withMessages([
                    'sections' => [
                        "Minimum selections cannot be greater than maximum selections for section: {$section->name}"
                    ],
                ]);
            }

            /*
             * Get selected items
             */
            $items = $sectionData['items'] ?? [];
            $itemCount = count($items);

            /*
             * Validate minimum selections against item count
             */
            if ($section->min_selections > $itemCount) {
                throw ValidationException::withMessages([
                    'sections' => [
                        "Section '{$section->name}' requires at least {$section->min_selections} item(s), but only {$itemCount} selected."
                    ],
                ]);
            }

            /*
             * Validate maximum selections against item count
             */
            if ($section->max_selections > $itemCount) {
                throw ValidationException::withMessages([
                    'sections' => [
                        "Section '{$section->name}' allows maximum {$section->max_selections} selection(s), but only {$itemCount} item(s) are available."
                    ],
                ]);
            }

            /*
             * Prevent duplicate menu items
             */
            $menuItemIds = collect($items)
                ->pluck('menu_item_id')
                ->filter()
                ->values();

            if ($menuItemIds->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'sections' => [
                        "Duplicate menu items are not allowed in section: {$section->name}"
                    ],
                ]);
            }

            /*
             * Delete old items
             */
            $section->items()->delete();

            /*
             * Create package items
             */
            $itemCounter = 1;
            foreach ($items as $index => $itemData) {
                $section->items()->create([
                    'menu_item_id' => $itemData['menu_item_id'],
                    'extra_price' => $itemData['extra_price'] ?? 0,
                    'is_default' => $itemData['is_default'] ?? 0, // Added is_default logic
                    'sort_order' => $itemCounter++,
                    'status' => $itemData['status'] ?? 1,
                ]);
            }
        }

        /*
         * Delete removed sections
         */
        $sectionsToDelete = array_diff(
            $existingSectionIds,
            $receivedSectionIds
        );

        if (!empty($sectionsToDelete)) {
            $package
                ->sections()
                ->whereIn('id', $sectionsToDelete)
                ->delete();
        }
    }
}