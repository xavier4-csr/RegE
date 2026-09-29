@extends('layouts.app')
@section('title', 'Business Directory')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="text-center">
        <span class="text-xs font-semibold text-blue-600 uppercase tracking-widest">Kenya Business Directory</span>
        <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Discover Kenyan Businesses</h1>
        <p class="text-gray-500 text-sm mt-2 max-w-lg mx-auto">
            Search thousands of verified companies across every industry and county in Kenya.
        </p>
    </div>

    {{-- Search bar --}}
    <form method="GET" action="{{ route('companies.index') }}"
          class="max-w-2xl mx-auto" x-data="{ showFilters: false }">

        {{-- Main search --}}
        <div class="flex gap-2 bg-white border border-gray-200 rounded-2xl shadow-md p-2">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search companies, industries, locations…"
                       class="w-full pl-9 pr-4 py-2.5 text-sm focus:outline-none rounded-xl">
            </div>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition flex-shrink-0">
                Search
            </button>
            <button type="button" @click="showFilters = !showFilters"
                    class="border border-gray-200 hover:bg-gray-50 px-3 py-2.5 rounded-xl text-gray-500 transition flex-shrink-0"
                    title="Filters">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>
        </div>

        {{-- Filter panel --}}
        <div x-show="showFilters" x-transition
             class="mt-3 bg-white border border-gray-200 rounded-2xl shadow-md p-4 grid grid-cols-2 md:grid-cols-4 gap-3">

            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Category</label>
                <select name="category"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">County</label>
                <select name="county"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All counties</option>
                    @foreach(['Nairobi','Mombasa','Kisumu','Nakuru','Eldoret','Thika','Malindi','Kitale','Garissa','Nyeri','Machakos','Meru','Kakamega','Kisii','Embu'] as $county)
                        <option value="{{ $county }}" {{ request('county') === $county ? 'selected' : '' }}>
                            {{ $county }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Sort by</label>
                <select name="sort"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="newest" {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>Newest first</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest rated</option>
                    <option value="views"  {{ request('sort') === 'views'  ? 'selected' : '' }}>Most viewed</option>
                    <option value="name"   {{ request('sort') === 'name'   ? 'selected' : '' }}>Name A–Z</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-semibold transition">
                    Apply
                </button>
                <a href="{{ route('companies.index') }}"
                   class="flex-1 text-center border border-gray-200 text-gray-600 hover:bg-gray-50 py-2 rounded-lg text-sm transition">
                    Clear
                </a>
            </div>
        </div>

        {{-- Active filters --}}
        @if(request('q') || request('category') || request('county'))
        <div class="flex flex-wrap gap-2 mt-3">
            @if(request('q'))
                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                    "{{ request('q') }}"
                    <a href="{{ request()->fullUrlWithoutQuery(['q']) }}" class="hover:text-blue-900 ml-1">✕</a>
                </span>
            @endif
            @if(request('county'))
                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                    📍 {{ request('county') }}
                    <a href="{{ request()->fullUrlWithoutQuery(['county']) }}" class="hover:text-blue-900 ml-1">✕</a>
                </span>
            @endif
            @if(request('category'))
                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                    🏷 {{ $categories->firstWhere('slug', request('category'))?->name }}
                    <a href="{{ request()->fullUrlWithoutQuery(['category']) }}" class="hover:text-blue-900 ml-1">✕</a>
                </span>
            @endif
        </div>
        @endif
    </form>

    {{-- Category pills --}}
    @if(!request('category'))
    <div class="flex flex-wrap gap-2 justify-center">
        @foreach($categories->take(8) as $cat)
        <a href="{{ route('companies.index', ['category' => $cat->slug]) }}"
           class="bg-white border border-gray-200 hover:border-blue-400 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-medium px-4 py-2 rounded-full transition shadow-sm">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Results header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            @if($companies->total() > 0)
                Showing <strong class="text-gray-900">{{ $companies->firstItem() }}–{{ $companies->lastItem() }}</strong>
                of <strong class="text-gray-900">{{ $companies->total() }}</strong> businesses
            @else
                No businesses found
            @endif
        </p>
        @auth
            <a href="{{ route('my-companies.create') }}"
               class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Register Yours
            </a>
        @endauth
    </div>

    {{-- Grid --}}
    @if($companies->isEmpty())
        <div class="text-center py-24">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-700 mb-1">No businesses found</h3>
            <p class="text-sm text-gray-400 mb-4">Try different search terms or remove some filters</p>
            @auth
                <a href="{{ route('my-companies.create') }}"
                   class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    Register the first one →
                </a>
            @endauth
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-5">
            @foreach($companies as $company)
                @include('partials.company-card', ['company' => $company])
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $companies->links() }}
        </div>
    @endif

</div>
@endsection