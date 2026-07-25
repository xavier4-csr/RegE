@extends('layouts.app')
@section('title', 'RegE — Business Registration Kenya')

@section('content')
<section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white px-8 py-16 mb-10 text-center shadow-xl">
    <div class="relative">
        <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4">🇰🇪 Built for Kenyan Entrepreneurs</span>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">
            Register your business.<br>
            <span class="text-blue-200">Stay compliant. Grow faster.</span>
        </h1>
        <p class="text-blue-100 text-lg max-w-xl mx-auto mb-8 leading-relaxed">
            RegE guides you from business name registration through KRA, SHIF, NSSF, county permits — and connects you to a network of Kenyan SMEs.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-700 hover:bg-blue-50 font-bold px-8 py-3 rounded-xl transition shadow-md text-sm">Get Started Free →</a>
            <a href="{{ route('companies.index') }}" class="border border-white/40 hover:bg-white/10 text-white font-semibold px-8 py-3 rounded-xl transition text-sm">Browse Directory</a>
        </div>
    </div>
</section>

<section class="mb-12">
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Everything in one place</h2>
    <p class="text-center text-gray-500 text-sm mb-8">No more jumping between government portals</p>
    <div class="grid md:grid-cols-3 gap-5">
        @foreach([
            ['🏢','Business Registration','Register your sole-prop, partnership, LLC or corporation with guided forms and AI-powered name suggestions.','bg-blue-50 border-blue-200'],
            ['📋','Compliance Tracking','Personalised checklist covering KRA PIN, SHIF, NSSF, county permits and annual returns with automated reminders.','bg-green-50 border-green-200'],
            ['🔍','B2B Directory','Get discovered by other businesses. Search thousands of verified Kenyan companies by industry and location.','bg-purple-50 border-purple-200'],
            ['🤖','AI Business Tools','AI suggests business names, writes professional descriptions, and answers compliance questions.','bg-orange-50 border-orange-200'],
            ['📱','M-Pesa Payments','Pay for premium features natively via M-Pesa — no international cards needed.','bg-pink-50 border-pink-200'],
            ['📄','Document Hub','Generate partnership agreements, resolution letters, and compliance certificates instantly.','bg-teal-50 border-teal-200'],
        ] as [$icon,$title,$desc,$bg])
        <div class="border rounded-xl p-5 {{ $bg }}">
            <div class="text-3xl mb-2">{{ $icon }}</div>
            <h3 class="font-bold text-gray-900 mb-1">{{ $title }}</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="bg-gray-900 text-white rounded-2xl px-8 py-10 mb-12">
    <h2 class="text-2xl font-bold text-center mb-8">How it works</h2>
    <div class="grid md:grid-cols-4 gap-6">
        @foreach([
            ['1','Create account','Register in under 2 minutes with your name, email and phone number.'],
            ['2','Register business','Fill in your company details. AI names your business and writes your description.'],
            ['3','Follow compliance','Your personalised checklist walks you through every post-registration step.'],
            ['4','Get discovered','Your business goes live in our directory — searchable by customers and partners.'],
        ] as [$num,$title,$desc])
        <div class="text-center">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-3">{{ $num }}</div>
            <h3 class="font-semibold mb-1">{{ $title }}</h3>
            <p class="text-gray-400 text-sm leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

@php
    $totalCo    = \App\Models\Company::where('status','active')->count();
    $totalUsers = \App\Models\User::count();
@endphp
<section class="bg-blue-600 text-white rounded-2xl px-8 py-8 mb-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div><div class="text-3xl font-extrabold">{{ number_format($totalCo) }}</div><div class="text-blue-200 text-sm mt-1">Registered Businesses</div></div>
        <div><div class="text-3xl font-extrabold">{{ number_format($totalUsers) }}</div><div class="text-blue-200 text-sm mt-1">Entrepreneurs</div></div>
        <div><div class="text-3xl font-extrabold">47</div><div class="text-blue-200 text-sm mt-1">Kenya Counties</div></div>
        <div><div class="text-3xl font-extrabold">8</div><div class="text-blue-200 text-sm mt-1">Compliance Steps</div></div>
    </div>
</section>

<section class="text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl py-12 px-8">
    <h2 class="text-3xl font-extrabold mb-3">Ready to register your business?</h2>
    <p class="text-blue-200 mb-6">Join thousands of entrepreneurs who chose RegE as their starting point.</p>
    <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 hover:bg-blue-50 font-bold px-10 py-3 rounded-xl transition shadow-md">Start for Free →</a>
</section>
@endsection