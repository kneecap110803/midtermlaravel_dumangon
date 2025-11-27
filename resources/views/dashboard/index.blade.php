@extends('layouts.app')

@section('title', 'Menu Items')

@section('content')
    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Menu Items</div>
            <div class="text-2xl font-bold">{{ $stats['totalItems'] }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Categories</div>
            <div class="text-2xl font-bold">{{ $stats['totalCategories'] }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Average Price</div>
            <div class="text-2xl font-bold">₱{{ number_format($stats['avgPrice'] ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Create form -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4">Add New Menu Item</h2>
        <form method="POST" action="{{ route('menu-items.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm mb-1">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Price</label>
                <input name="price" type="number" step="0.01" value="{{ old('price') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="3">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm mb-1">Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    <option value="">No category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_available" value="1" @checked(old('is_available', true))>
                <span class="text-sm">Available</span>
            </div>
            <div class="md:col-span-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded shadow p-4 mb-4">
        <form method="GET" action="{{ route('menu-items.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Search by name</label>
                <input name="search" value="{{ $search }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Filter by category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Sort</label>
                <select name="sort" class="w-full border rounded px-3 py-2">
                    <option value="desc" @selected($sort==='desc')>Newest to oldest</option>
                    <option value="asc" @selected($sort==='asc')>Oldest to newest</option>
                </select>
            </div>
            <div class="md:col-span-4">
                <button class="bg-gray-800 text-white px-4 py-2 rounded">Apply</button>
                <a href="{{ route('menu-items.index') }}" class="ml-2 text-sm text-gray-600 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-sm text-gray-600">
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Created</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm">
            @foreach($menuItems as $item)
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                    <td class="px-4 py-2">₱{{ number_format($item->price, 2) }}</td>
                    <td class="px-4 py-2">{{ $item->category?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-white {{ $item->is_available ? 'bg-green-600' : 'bg-gray-500' }}">
                            {{ $item->is_available ? 'Available' : 'Not Available' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button
                            class="px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600"
                            onclick="openEditModal({{ $item->id }}, '{{ e($item->name) }}', '{{ $item->price }}', '{{ e($item->description) }}', '{{ $item->category_id }}', {{ $item->is_available ? 'true' : 'false' }})">
                            Edit
                        </button>
                        <form action="{{ route('menu-items.destroy', $item) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $menuItems->links() }}
        </div>
    </div>
@endsection

@section('modals')
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded shadow w-full max-w-lg">
            <div class="px-4 py-2 border-b flex justify-between items-center">
                <h3 class="font-semibold">Edit Menu Item</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="editForm" method="POST" class="p-4 space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm mb-1">Name</label>
                    <input name="name" id="edit_name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm mb-1">Price</label>
                    <input name="price" id="edit_price" type="number" step="0.01" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm mb-1">Description</label>
                    <textarea name="description" id="edit_description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1">Category</label>
                    <select name="category_id" id="edit_category_id" class="w-full border rounded px-3 py-2">
                        <option value="">No category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_available" id="edit_is_available" value="1">
                    <span class="text-sm">Available</span>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-3 py-2 rounded border">Cancel</button>
                    <button class="px-3 py-2 rounded bg-blue-600 text-white">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, price, description, category_id, is_available) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description !== 'null' ? description : '';
            document.getElementById('edit_category_id').value = category_id || '';
            document.getElementById('edit_is_available').checked = !!is_available;
            const form = document.getElementById('editForm');
            form.action = "{{ url('/menu-items') }}/" + id;
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
