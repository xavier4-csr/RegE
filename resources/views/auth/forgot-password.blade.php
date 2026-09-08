@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
<div class="w-full max-w-md">

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
            <h1 class="text-2xl font-bold text-white">Forgot Password?</h1>
            <p class="text-blue-200 text-sm mt-1">We'll email you a password reset link</p>
        </div>

        <div class="px-8 py-7">
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    Send Reset Link
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">← Back to Sign in</a>
            </p>
        </div>
    </div>

</div>
</div>
@endsection
