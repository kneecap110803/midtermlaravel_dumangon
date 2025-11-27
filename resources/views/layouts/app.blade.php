<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Restaurant Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r">
            <div class="p-6 border-b">
                <div class="text-xl font-bold">🍽 Restaurant</div>
                <div class="text-xs text-gray-500">Management System</div>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('menu-items.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('menu-items.*') ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('categories.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('categories.*') ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
                    Categories
                </a>
            </nav>
            <div class="mt-auto p-4 border-t">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-gray-300"></div>
                    <div>
                        <div class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button class="text-xs text-red-600 hover:underline">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <main class="flex-1">
            <header class="bg-white border-b px-6 py-4">
                <h1 class="text-lg font-semibold">@yield('title')</h1>
            </header>
            <div class="p-6">
                @include('components.alert-success')
                @include('components.alert-error')
                @yield('content')
            </div>
        </main>
    </div>

    @yield('modals')
</body>
</html>
