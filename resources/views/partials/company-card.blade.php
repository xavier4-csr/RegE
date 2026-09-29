{{-- resources/views/partials/company-card.blade.php --}}
@php
    $companyGradientIndex = abs(crc32((string) ($company->company_name ?? ''))) % 5;
@endphp

<a href="{{ route('companies.show', $company) }}"
   class="group bg-white rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-xl transition-all duration-200 overflow-hidden flex flex-col">

    {{-- Banner + Logo --}}
    <div class="relative h-24 flex-shrink-0"
         style="background: linear-gradient(135deg,
            {{ ['#1e3a5f','#1e3a5f','#1a3650','#162d47','#1e3a5f'][$companyGradientIndex] }},
            {{ ['#2563eb','#4f46e5','#0891b2','#059669','#7c3aed'][$companyGradientIndex] }});">

        {{-- Pattern --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>

        {{-- Badges --}}
        <div class="absolute top-2 right-2 flex gap-1">
            @if($company->is_featured)
                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    ⭐ Featured
                </span>
            @endif
            @if($company->is_verified)
                <span class="bg-white/90 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    ✓ Verified
                </span>
            @endif
        </div>

        {{-- Logo --}}
        <div class="absolute -bottom-6 left-4">
            <div class="w-14 h-14 rounded-xl border-2 border-white shadow-md overflow-hidden bg-white">
                <img src="{{ $company->logo }}"
                     alt="{{ $company->company_name }}"
                     class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="pt-8 px-4 pb-4 flex flex-col flex-1">

        {{-- Name + category --}}
        <div class="mb-2">
            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition text-sm leading-tight truncate">
                {{ $company->company_name }}
            </h3>
            @if($company->category)
                <span class="inline-block mt-1 bg-blue-50 text-blue-700 text-xs font-medium px-2 py-0.5 rounded-md">
                    {{ $company->category->name }}
                </span>
            @endif
        </div>

        {{-- Description --}}
        @if($company->company_description)
            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1 mb-3">
                {{ $company->company_description }}
            </p>
        @else
            <div class="flex-1 mb-3"></div>
        @endif

        {{-- Footer row --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div class="flex items-center gap-1 text-xs text-gray-500">
                <svg class="w-3 h-3 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span class="truncate max-w-20">{{ $company->city ?? $company->county ?? 'Kenya' }}</span>
            </div>

            <div class="flex items-center gap-2">
                {{-- Rating --}}
                @if(($company->reviews_avg_rating ?? $company->average_rating ?? 0) > 0)
                    @php $r = round($company->reviews_avg_rating ?? $company->average_rating, 1); @endphp
                    <div class="flex items-center gap-0.5">
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700">{{ $r }}</span>
                    </div>
                @else
                    <span class="text-xs text-gray-300">No reviews</span>
                @endif

                {{-- Views --}}
                <div class="flex items-center gap-0.5 text-xs text-gray-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($company->profile_views) }}
                </div>
            </div>
        </div>
    </div>
</a>