{{-- Table --}}
<div class="overflow-x-auto border border-gray-200 rounded-lg">
    <table class="w-full text-sm text-left text-gray-600">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 font-semibold">Name</th>
                <th class="px-6 py-4 font-semibold">Price</th>
                <th class="px-6 py-4 font-semibold">Sections</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($packages as $package)
                <tr class="bg-white hover:bg-gray-50 transition-colors">

                    {{-- Name --}}
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $package->name }}
                    </td>

                    {{-- Price --}}
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900">
                            ₹{{ $package->price }}
                        </span>
                        <span class="text-xs text-gray-500">
                            ({{ str_replace('_', ' ', $package->price_type) }})
                        </span>
                    </td>

                    {{-- Sections --}}
                    <td class="px-6 py-4">
                        <span
                            class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-blue-200">
                            {{ $package->sections_count }} Sections
                        </span>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @if ($package->status == 1)
                            <span
                                class="text-green-700 bg-green-100 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-200">
                                Active
                            </span>
                        @else
                            <span
                                class="text-red-700 bg-red-100 px-2.5 py-1 rounded-md text-xs font-semibold border border-red-200">
                                Inactive
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-end items-center gap-3">
                            <a href="{{ route('admin.catering-packages.show', $package->id) }}"
                                class="text-blue-600 hover:text-blue-900 font-medium">
                                View
                            </a>
                            <a href="{{ route('admin.catering-packages.edit', $package->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                Edit
                            </a>

                            <form action="{{ route('admin.catering-packages.destroy', $package->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium"
                                    onclick="return confirm('Are you sure you want to delete this package?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No catering packages found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($packages->hasPages())
    <div class="mt-6">
        {{ $packages->links() }}
    </div>
@endif

{{-- Results count --}}
<div class="mt-3 text-sm text-gray-500">
    Showing {{ $packages->firstItem() ?? 0 }}
    to {{ $packages->lastItem() ?? 0 }}
    of {{ $packages->total() }} packages
</div>
