<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r transform transition-transform duration-300 ease-in-out" 
             x-data="{ open: true }" 
             :class="{'translate-x-0': open, '-translate-x-full': !open}">
            <div class="h-16 flex items-center justify-between px-4 border-b">
                <h1 class="text-2xl font-semibold text-gray-800">DMS</h1>
                <button @click="open = !open" class="lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="mt-8 space-y-4 px-4">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </div>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </div>
                    </a>
                    <a href="{{ route('admin.tenants.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.tenants.*') ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Tenants
                        </div>
                    </a>
                    <a href="{{ route('admin.rooms.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.rooms.*') ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Rooms
                        </div>
                    </a>
                @endif
            </nav>
            <div class="absolute bottom-0 w-full border-t">
                <div class="px-4 py-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center w-full text-left">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="user avatar" class="w-8 h-8 rounded-full mr-3">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-lg shadow-lg py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <div class="fixed top-0 right-0 left-0 z-20 lg:left-64 bg-white border-b">
                <div class="flex items-center justify-between h-16 px-4">
                    <button @click="$el.previousElementSibling.querySelector('[x-data]').__x.$data.open = true" class="lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        @if (isset($header))
                            <h2 class="text-xl font-semibold text-gray-800">
                                {{ $header }}
                            </h2>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <!-- Add any top-right navigation items here -->
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="pt-16">
                <div class="py-6 px-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
