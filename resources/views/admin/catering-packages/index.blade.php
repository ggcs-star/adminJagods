@extends('admin.app')

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm mb-6 border border-gray-100">
            <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <h4 class="text-xl font-semibold text-gray-800">Catering Packages</h4>

                <a href="{{ route('admin.catering-packages.create') }}"
                    style="background-color: #dc2626 !important; color: white !important;"
                    class="text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>

                    Create New Package
                </a>
            </div>

            <div class="p-5">
                <!-- Search Filter -->
                <form action="{{ route('admin.catering-packages.index') }}" method="GET" class="flex gap-3 mb-6">
                    <div class="flex-1 max-w-md">
                        <input type="text" name="search"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Search packages by name..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Table -->
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
                            @forelse($packages as $package)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $package->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-900">₹{{ $package->price }}</span>
                                        <span
                                            class="text-xs text-gray-500">({{ str_replace('_', ' ', $package->price_type) }})</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-blue-200">
                                            {{ $package->sections_count }} Sections
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($package->status == 1)
                                            <span
                                                class="db-table-badge text-green-700 bg-green-100 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-200">Active</span>
                                        @else
                                            <span
                                                class="db-table-badge text-red-700 bg-red-100 px-2.5 py-1 rounded-md text-xs font-semibold border border-red-200">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-3">
                                        <a href="{{ route('admin.catering-packages.edit', $package->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        <form action="{{ route('admin.catering-packages.destroy', $package->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium"
                                                onclick="return confirm('Are you sure you want to delete this package?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No catering packages
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $packages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
