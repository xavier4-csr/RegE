<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'RegE')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

{{-- Sidebar --}}
<aside class="w-56 bg-gray-900 text-white min-h-screen flex flex-col fixed top-0 left-0 z-40">
    <div class="px-5 py-4 border-b border-gray-700">
        <a href="{{ route('home') }}" class="text-blue-400 font-bold text-lg">RegE</a>
        <div class="text-gray-400 text-xs mt-0.5">Admin Panel</div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            📊 Dashboard
        </a>
        <a href="{{ route('admin.users') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            👥 Users
        </a>
        <a href="{{ route('admin.companies') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.companies') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            🏢 Companies
        </a>
        <a href="{{ route('admin.payments') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.payments') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            💳 Payments
        </a>
        <a href="{{ route('admin.compliance-steps') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.compliance-steps') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            📋 Compliance Steps
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-gray-700">
        <div class="text-xs text-gray-400 mb-2">{{ auth()->user()->full_name }}</div>
        <a href="{{ route('dashboard') }}" class="text-xs text-blue-400 hover:underline block mb-1">← User dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-xs text-red-400 hover:underline">Logout</button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="ml-56 flex-1 flex flex-col min-h-screen">
    <header class="bg-white border-b px-6 py-3 flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-900">@yield('title')</h1>
        <span class="text-xs text-gray-400">{{ now()->format('d M Y, H:i') }}</span>
    </header>

    <div class="p-6 flex-1">
        @foreach(['success','error','info'] as $type)
            @if(session($type))
                <div class="mb-4 px-4 py-3 rounded-lg text-sm
                    {{ $type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : ($type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>
