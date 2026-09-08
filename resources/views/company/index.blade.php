@extends('layouts.app')
@section('title', 'Business Directory')

@section('content')
<div class="space-y-6">

    <div class="text-center">
        <h1 class="text-3xl font-extrabold text-gray-900">🔍 Business Directory</h1>
        <p class="text-gray-500 text-sm mt-1">Discover verified Kenyan businesses across every industry</p>
    </div>

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('companies.index') }}"
          class="bg-white border rounded-2xl shadow-sm p-5"
          x-data="{ showFilters: false }">

        <div class="flex gap-3">
            <div class="flex-1 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search companies, industries…"
                       class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition">
                Search
            </button>
            <button type="button" @click="showFilters = !showFilters"
                    class="border border-gray-200 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm text-gray-600 transition">
                Filters ⚙
            </button>
        </div>

        <div x-show="showFilters" x-transition class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3 pt-4 border-t">
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block">Category</label>
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
                <label class="text-xs font-semibold text-gray-500 mb-1 block">County</label>
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
                <label class="text-xs font-semibold text-gray-500 mb-1 block">Sort by</label>
                <select name="sort"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="newest"  {{ request('sort','newest') === 'newest'  ? 'selected' : '' }}>Newest first</option>
                    <option value="rating"  {{ request('sort') === 'rating'  ? 'selected' : '' }}>Highest rated</option>
                    <option value="views"   {{ request('sort') === 'views'   ? 'selected' : '' }}>Most viewed</option>
                    <option value="name"    {{ request('sort') === 'name'    ? 'selected' : '' }}>Name A–Z</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('companies.index') }}"
                   class="w-full text-center border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg px-3 py-2 text-sm transition">
                    Clear filters ✕
                </a>
            </div>
        </div>
    </form>

    {{-- Results header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Showing <strong>{{ $companies->firstItem() ?? 0 }}–{{ $companies->lastItem() ?? 0 }}</strong>
            of <strong>{{ $companies->total() }}</strong> businesses
            @if(request('q')) for <em>"{{ request('q') }}"</em> @endif
        </p>
        @auth
            <a href="{{ route('my-companies.create') }}"
               class="text-sm bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition">
                + Register Yours
            </a>
        @endauth
    </div>

    {{-- Grid --}}
    @if($companies->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <div class="text-5xl mb-3">🔦</div>
            <p class="font-semibold text-gray-600">No businesses found</p>
            <p class="text-sm mt-1">Try different search terms or remove filters</p>
            @auth
                <a href="{{ route('my-companies.create') }}"
                   class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
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
        <div class="mt-6">{{ $companies->links() }}</div>
    @endif

</div>
@endsection
