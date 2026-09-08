{{-- resources/views/partials/company-card.blade.php --}}
<a href="{{ route('companies.show', $company) }}"
   class="group bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition overflow-hidden block">

    <div class="h-28 bg-gradient-to-br from-gray-100 to-gray-200 relative flex items-center justify-center">
        <img src="{{ $company->logo }}"
             alt="{{ $company->company_name }}"
             class="w-16 h-16 rounded-xl object-cover shadow-sm border border-white">

        @if($company->is_featured)
            <span class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">
                ⭐ Featured
            </span>
        @endif
        @if($company->is_verified)
            <span class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                ✓ Verified
            </span>
        @endif
    </div>

    <div class="p-4">
        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition text-sm truncate">
            {{ $company->company_name }}
        </h3>
        @if($company->category)
            <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-full mt-1">
                {{ $company->category->name }}
            </span>
        @endif
        @if($company->company_description)
            <p class="text-gray-500 text-xs mt-2 leading-relaxed line-clamp-2">
                {{ $company->company_description }}
            </p>
        @endif

        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <div class="text-xs text-gray-500">
                📍 {{ $company->city ?? $company->county ?? 'Kenya' }}
            </div>
            <div class="flex items-center gap-1">
                @if($company->average_rating > 0)
                    <span class="text-yellow-400 text-xs">★</span>
                    <span class="text-xs font-medium text-gray-700">{{ $company->average_rating }}</span>
                @else
                    <span class="text-xs text-gray-400">No reviews</span>
                @endif
            </div>
        </div>
    </div>
</a>
