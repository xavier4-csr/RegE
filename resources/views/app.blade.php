<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RegE') — Business Registration Kenya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

<nav class="bg-gray-900 text-white shadow-lg" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight text-blue-400">RegE</a>
        <div class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('companies.index') }}" class="hover:text-blue-300 transition">Directory</a>
            <a href="{{ route('contact') }}" class="hover:text-blue-300 transition">Contact</a>
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Dashboard</a>
                <a href="{{ route('my-companies.create') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-lg transition font-medium">+ Register Business</a>
                <div class="relative" x-data="{ show: false }">
                    <button @click="show = !show" class="flex items-center gap-2">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover border-2 border-blue-400">
                        <span class="text-sm">{{ auth()->user()->first_name }}</span>
                    </button>
                    <div x-show="show" @click.away="show = false" class="absolute right-0 mt-2 w-44 bg-white text-gray-800 rounded-lg shadow-xl z-50 border">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">👤 Profile</a>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">📊 Dashboard</a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="hover:text-blue-300 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-lg transition font-medium">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto w-full px-4 mt-4 space-y-2">
    @foreach(['success','error','info','warning'] as $type)
        @if(session($type))
            <div class="bg-{{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : ($type === 'info' ? 'blue' : 'yellow')) }}-50 border px-4 py-3 rounded-lg">
                {{ session($type) }}
            </div>
        @endif
    @endforeach
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
</div>

<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-6">
    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-400 text-sm mt-12">
    <div class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-3 gap-6">
        <div>
            <h3 class="text-white font-semibold mb-2">RegE</h3>
            <p>Empowering African entrepreneurs through streamlined business registration and compliance.</p>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-2">Quick Links</h3>
            <ul class="space-y-1">
                <li><a href="{{ route('companies.index') }}" class="hover:text-white">Business Directory</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Register Business</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-2">Legal</h3>
            <ul class="space-y-1">
                <li><a href="#" class="hover:text-white">Terms of Use</a></li>
                <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-800 text-center py-4">&copy; {{ date('Y') }} RegE — Built for African entrepreneurs.</div>
</footer>

@stack('scripts')
</body>
</html>