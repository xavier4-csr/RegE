@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
<div class="w-full max-w-md">

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
            <h1 class="text-2xl font-bold text-white">Welcome back</h1>
            <p class="text-blue-200 text-sm mt-1">Sign in to your RegE account</p>
        </div>

        <div class="px-8 py-7 space-y-5">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}" required autofocus
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-sm font-semibold text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                    <input id="password" type="password" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-blue-600">
                    <label for="remember" class="text-sm text-gray-600">Keep me signed in</label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Create one free →</a>
            </p>
        </div>
    </div>

</div>
</div>
@endsection
