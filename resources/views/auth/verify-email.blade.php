@extends('layouts.app')
@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
<div class="max-w-md w-full bg-white rounded-2xl border shadow-md p-8 text-center">
    <div class="text-6xl mb-4">📬</div>
    <h1 class="text-xl font-bold text-gray-900 mb-2">Check your inbox</h1>
    <p class="text-gray-600 text-sm leading-relaxed mb-6">
        We sent a verification link to
        <strong>{{ auth()->user()->email }}</strong>.
        Click the link to activate your account.
    </p>

    @if(session('status') === 'verification-link-sent')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
            ✅ A fresh verification link has been sent to your email.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 hover:underline">
            Sign out and use a different account
        </button>
    </form>
</div>
</div>
@endsection
