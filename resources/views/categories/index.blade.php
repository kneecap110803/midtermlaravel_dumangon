@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <!-- Create form -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4">Add New Category</h2>
        <form method="POST" action="{{ route('categories.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div class="md:col-span-1">
                <label class="block text-sm mb-1">Category name</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-sm text-gray-600">
            <tr>
                <th class="px-4 py-2">Category name</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Related items</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm">
            @foreach($categories as $category)
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-2">{{ $category->description ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $category->menu_items_count }} items</td>
                    <td class="px-4 py-2 space-x-2">
                        <button
                            class="px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600"
                            onclick="openCategoryEdit({{ $category->id }}, '{{ e($category->name) }}', '{{ e($category->description) }}')">
                            Edit
                        </button>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this category? Related items will be set to N/A.');">
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
            {{ $categories->links() }}
        </div>
    </div>
@endsection

@section('modals')
    <!-- Edit Category Modal -->
    <div id="catEditModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded shadow w-full max-w-lg">
            <div class="px-4 py-2 border-b flex justify-between items-center">
                <h3 class="font-semibold">Edit Category</h3>
                <button onclick="closeCategoryEdit()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="catEditForm" method="POST" class="p-4 space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm mb-1">Category name</label>
                    <input name="name" id="cat_name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm mb-1">Description</label>
                    <textarea name="description" id="cat_description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCategoryEdit()" class="px-3 py-2 rounded border">Cancel</button>
                    <button class="px-3 py-2 rounded bg-blue-600 text-white">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCategoryEdit(id, name, description) {
            document.getElementById('catEditModal').classList.remove('hidden');
            document.getElementById('cat_name').value = name;
            document.getElementById('cat_description').value = description !== 'null' ? description : '';
            const form = document.getElementById('catEditForm');
            form.action = "{{ url('/categories') }}/" + id;
        }
        function closeCategoryEdit() {
            document.getElementById('catEditModal').classList.add('hidden');
        }
    </script>
@endsection
