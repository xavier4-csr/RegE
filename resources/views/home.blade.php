@extends('layouts.app')
@section('title', 'RegE — Business Registration Kenya')

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────────────────── --}}
<section class="relative rounded-2xl overflow-hidden mb-14" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);">

    {{-- Grid pattern overlay --}}
    <div class="absolute inset-0 opacity-5"
         style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px); background-size: 40px 40px;"></div>

    {{-- Glow accents --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-10 blur-3xl" style="background: radial-gradient(circle, #3b82f6, transparent);"></div>
    <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-10 blur-3xl" style="background: radial-gradient(circle, #6366f1, transparent);"></div>

    <div class="relative px-8 py-20 text-center">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-blue-200 text-xs font-medium px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            Live in Kenya — Serving 47 Counties
        </div>

        {{-- Headline --}}
        <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-5 tracking-tight">
            The smarter way to<br>
            <span style="background: linear-gradient(90deg, #60a5fa, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                register &amp; grow your business
            </span>
        </h1>

        <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
            RegE combines business registration guidance, post-registration compliance tracking,
            AI-powered tools, and a B2B directory — all built for Kenyan entrepreneurs.
        </p>

        {{-- CTAs --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-12">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3.5 rounded-xl transition shadow-lg shadow-blue-900/40 text-sm">
                Get Started Free
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('companies.index') }}"
               class="inline-flex items-center gap-2 border border-white/20 hover:bg-white/10 text-white font-medium px-8 py-3.5 rounded-xl transition text-sm backdrop-blur">
                Browse Directory
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </a>
        </div>

        {{-- Trust stats --}}
        @php
            $totalCo    = \App\Models\Company::where('status','active')->count();
            $totalUsers = \App\Models\User::count();
        @endphp
        <div class="flex flex-wrap justify-center gap-8 text-sm">
            <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ number_format($totalUsers) }}+</div>
                <div class="text-slate-400 text-xs mt-0.5">Entrepreneurs</div>
            </div>
            <div class="w-px bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">{{ number_format($totalCo) }}+</div>
                <div class="text-slate-400 text-xs mt-0.5">Registered Businesses</div>
            </div>
            <div class="w-px bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">47</div>
                <div class="text-slate-400 text-xs mt-0.5">Kenya Counties</div>
            </div>
            <div class="w-px bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">8</div>
                <div class="text-slate-400 text-xs mt-0.5">Compliance Steps</div>
            </div>
        </div>
    </div>
</section>

{{-- ── Trusted by section ───────────────────────────────────────────────── --}}
<section class="mb-14 text-center">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-6">Compliance steps covering</p>
    <div class="flex flex-wrap justify-center gap-6">
        @foreach(['KRA iTax', 'eCitizen BRS', 'SHIF', 'NSSF', 'County Permits', 'Annual Returns', 'Beneficial Ownership', 'VAT Registration'] as $item)
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 shadow-sm text-sm text-gray-700 font-medium">
            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ $item }}
        </div>
        @endforeach
    </div>
</section>

{{-- ── Features ─────────────────────────────────────────────────────────── --}}
<section class="mb-14">
    <div class="text-center mb-10">
        <span class="text-xs font-semibold text-blue-600 uppercase tracking-widest">Platform Features</span>
        <h2 class="text-3xl font-extrabold text-gray-900 mt-2">Everything you need, in one place</h2>
        <p class="text-gray-500 mt-2 max-w-xl mx-auto">No more jumping between government portals. RegE guides you from registration all the way through ongoing compliance.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        @foreach([
            ['📝', 'Business Registration', 'Step-by-step guidance through BRS registration for sole proprietorships, partnerships, LLCs and corporations — with AI-powered business name suggestions.', 'border-blue-100 hover:border-blue-300', 'bg-blue-50', 'text-blue-700'],
            ['✅', 'Compliance Tracking', 'Personalised post-registration checklist covering KRA PIN, SHIF, NSSF, county single business permits, VAT registration and annual BRS returns.', 'border-green-100 hover:border-green-300', 'bg-green-50', 'text-green-700'],
            ['🔍', 'Business Directory', 'Get discovered by customers and B2B partners. Search verified Kenyan companies by industry, location, and rating with a powerful filtering system.', 'border-purple-100 hover:border-purple-300', 'bg-purple-50', 'text-purple-700'],
            ['🤖', 'AI-Powered Tools', 'Generate unique business names, write professional company descriptions, and get instant answers to compliance questions — powered by advanced AI.', 'border-orange-100 hover:border-orange-300', 'bg-orange-50', 'text-orange-700'],
            ['📱', 'M-Pesa Payments', 'Pay for premium features natively via M-Pesa STK Push. No international cards, no friction — built entirely for the Kenyan payment ecosystem.', 'border-pink-100 hover:border-pink-300', 'bg-pink-50', 'text-pink-700'],
            ['📄', 'Document Management', 'Store registration certificates, permits and legal documents in one secure place. Generate partnership agreements and compliance certificates on demand.', 'border-teal-100 hover:border-teal-300', 'bg-teal-50', 'text-teal-700'],
        ] as [$icon, $title, $desc, $border, $bg, $text])
        <div class="bg-white border {{ $border }} rounded-2xl p-6 shadow-sm hover:shadow-md transition group">
            <div class="{{ $bg }} w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                {{ $icon }}
            </div>
            <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ── How it works ─────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl px-8 py-14 mb-14">
    <div class="text-center mb-10">
        <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Simple Process</span>
        <h2 class="text-3xl font-extrabold mt-2">From idea to listed business in 4 steps</h2>
    </div>
    <div class="grid md:grid-cols-4 gap-6 relative">
        {{-- Connecting line --}}
        <div class="hidden md:block absolute top-6 left-1/4 right-1/4 h-px bg-white/10" style="left:12.5%; right:12.5%;"></div>

        @foreach([
            ['01', 'Create Account', 'Register in under 2 minutes with your name, email and phone number.', 'bg-blue-600'],
            ['02', 'Register Business', 'Fill in your company details. Use AI to name your business and write your description.', 'bg-indigo-600'],
            ['03', 'Complete Compliance', 'Follow your personalised checklist covering every post-registration obligation.', 'bg-purple-600'],
            ['04', 'Get Discovered', 'Your business goes live in our directory, searchable by customers and partners nationwide.', 'bg-violet-600'],
        ] as [$num, $title, $desc, $colour])
        <div class="text-center relative">
            <div class="{{ $colour }} w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-sm shadow-lg">
                {{ $num }}
            </div>
            <h3 class="font-semibold mb-2 text-white">{{ $title }}</h3>
            <p class="text-slate-400 text-sm leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ── Featured businesses ──────────────────────────────────────────────── --}}
@php
    $recent = \App\Models\Company::where('status','active')->with('category')->latest()->limit(6)->get();
@endphp

@if($recent->count() > 0)
<section class="mb-14">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Recently Registered</h2>
            <p class="text-gray-500 text-sm mt-0.5">Businesses that just joined the RegE network</p>
        </div>
        <a href="{{ route('companies.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
            View all
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid md:grid-cols-3 gap-5">
        @foreach($recent as $company)
            @include('partials.company-card', ['company' => $company])
        @endforeach
    </div>
</section>
@endif

{{-- ── Testimonials ─────────────────────────────────────────────────────── --}}
<section class="mb-14">
    <div class="text-center mb-8">
        <span class="text-xs font-semibold text-blue-600 uppercase tracking-widest">Testimonials</span>
        <h2 class="text-2xl font-extrabold text-gray-900 mt-2">What entrepreneurs say</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-5">
        @foreach([
            ['"RegE saved me hours of running between government offices. The compliance checklist is a game-changer for any new business owner."', 'James K.', 'Retail Business, Nairobi', 'JK'],
            ['"The AI business name tool suggested a name I never would have thought of. My clients love it and it was available immediately."', 'Fatuma A.', 'Consulting Firm, Mombasa', 'FA'],
            ['"Finally a platform that truly understands Kenyan entrepreneurs. The M-Pesa integration makes every transaction seamless."', 'Peter M.', 'Tech Startup, Kisumu', 'PM'],
        ] as [$quote, $name, $biz, $initials])
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex gap-1 text-yellow-400 text-sm mb-4">★★★★★</div>
            <p class="text-gray-600 text-sm leading-relaxed italic mb-5">{{ $quote }}</p>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                    {{ $initials }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900 text-sm">{{ $name }}</div>
                    <div class="text-gray-400 text-xs">{{ $biz }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
<section class="relative rounded-2xl overflow-hidden text-center py-16 px-8 mb-4" style="background: linear-gradient(135deg, #1e40af, #4f46e5);">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    <div class="relative">
        <h2 class="text-3xl font-extrabold text-white mb-3">Ready to register your business?</h2>
        <p class="text-blue-200 mb-8 max-w-md mx-auto">Join Kenyan entrepreneurs who chose RegE as their starting point. Free to get started.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="inline-block bg-white text-blue-700 hover:bg-blue-50 font-bold px-10 py-3.5 rounded-xl transition shadow-md text-sm">
                Create Free Account →
            </a>
            <a href="{{ route('contact') }}"
               class="inline-block border border-white/30 hover:bg-white/10 text-white font-medium px-10 py-3.5 rounded-xl transition text-sm">
                Talk to Us
            </a>
        </div>
    </div>
</section>

@endsection