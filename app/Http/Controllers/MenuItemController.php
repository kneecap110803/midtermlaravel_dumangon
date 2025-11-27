<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $sort = $request->query('sort', 'desc'); // 'desc' or 'asc'

        $query = MenuItem::with('category');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $query->orderBy('created_at', $sort);

        $menuItems = $query->paginate(10)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        $stats = [
            'totalItems' => MenuItem::count(),
            'totalCategories' => Category::count(),
            'avgPrice' => MenuItem::avg('price'), // example stat
        ];

        return view('dashboard.index', compact('menuItems', 'categories', 'stats', 'search', 'categoryId', 'sort'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = (bool)($validated['is_available'] ?? false);

        MenuItem::create($validated);

        return back()->with('success', 'Menu item created successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = (bool)($validated['is_available'] ?? false);

        $menuItem->update($validated);

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return back()->with('success', 'Menu item deleted successfully.');
    }
}

