@extends('layouts.app')
@section('title', $company->company_name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Back --}}
    <a href="{{ route('companies.index') }}"
       class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Directory
    </a>

    {{-- Hero card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Banner --}}
        <div class="h-36 relative"
             style="background: linear-gradient(135deg, #0f172a, #1e40af);">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>

            {{-- Edit button for owner --}}
            @auth
                @if($company->user_id === auth()->id())
                    <a href="{{ route('my-companies.edit', $company) }}"
                       class="absolute top-3 right-3 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-xs font-medium px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                @endif
            @endauth
        </div>

        <div class="px-6 pb-6">
            {{-- Logo + badges --}}
            <div class="flex items-end justify-between -mt-10 mb-4">
                <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0">
                    <img src="{{ $company->logo }}" alt="{{ $company->company_name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex gap-2 pb-1">
                    @if($company->is_verified)
                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified
                        </span>
                    @endif
                    @if($company->is_featured)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">⭐ Featured</span>
                    @endif
                </div>
            </div>

            {{-- Name & meta --}}
            <h1 class="text-2xl font-extrabold text-gray-900">{{ $company->company_name }}</h1>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2">
                @if($company->category)
                    <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $company->category->name }}
                    </span>
                @endif
                <span class="text-sm text-gray-500 capitalize">{{ str_replace('_',' ', $company->business_type) }}</span>
                @if($company->city || $company->county)
                    <span class="flex items-center gap-1 text-sm text-gray-500">
                        <svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ collect([$company->city, $company->county])->filter()->join(', ') }}
                    </span>
                @endif
                <span class="flex items-center gap-1 text-sm text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ number_format($company->profile_views) }} views
                </span>
                @if($company->review_count > 0)
                    <span class="flex items-center gap-1 text-sm text-gray-500">
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ $company->average_rating }} ({{ $company->review_count }} reviews)
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Left: description + reviews --}}
        <div class="md:col-span-2 space-y-5">

            {{-- About --}}
            @if($company->company_description)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    About
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $company->company_description }}</p>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Reviews
                    </h2>
                    @if($company->review_count > 0)
                        <div class="flex items-center gap-1">
                            @for($i=1;$i<=5;$i++)
                                <svg class="w-4 h-4 {{ $i <= $company->average_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-sm font-semibold text-gray-700 ml-1">{{ $company->average_rating }}</span>
                        </div>
                    @endif
                </div>

                @if($company->reviews->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-3xl mb-2">💬</div>
                        <p class="text-gray-400 text-sm">No reviews yet. Be the first to review!</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($company->reviews as $review)
                        <div class="border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $review->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-800">{{ $review->user->full_name }}</span>
                                        <span class="text-xs text-gray-400 ml-1.5">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-0.5">
                                    @for($i=1;$i<=5;$i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            @if($review->title)
                                <p class="text-sm font-semibold text-gray-800">{{ $review->title }}</p>
                            @endif
                            @if($review->body)
                                <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">{{ $review->body }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Write a review --}}
                @auth
                    @if($company->user_id !== auth()->id())
                    <div class="mt-5 pt-5 border-t border-gray-100" x-data="{ rating: 0 }">
                        <h3 class="font-semibold text-gray-900 mb-3 text-sm">Write a Review</h3>
                        <form method="POST" action="{{ route('reviews.store', $company) }}" class="space-y-3">
                            @csrf
                            <div class="flex gap-1">
                                @for($i=1;$i<=5;$i++)
                                <button type="button" @click="rating = {{ $i }}"
                                        class="text-2xl transition hover:scale-110"
                                        :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'">★</button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                            <input type="text" name="title" placeholder="Review title (optional)"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <textarea name="body" rows="3" placeholder="Share your experience with this business…"
                                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                                Submit Review
                            </button>
                        </form>
                    </div>
                    @endif
                @else
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                            Sign in to leave a review
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Right: contact + details --}}
        <div class="space-y-4">

            {{-- Contact --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide">Contact</h2>
                <div class="space-y-3">
                    @if($company->phone)
                    <a href="tel:{{ $company->phone }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-blue-600 group">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition">📞</div>
                        {{ $company->phone }}
                    </a>
                    @endif
                    @if($company->email)
                    <a href="mailto:{{ $company->email }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-blue-600 group break-all">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition flex-shrink-0">✉️</div>
                        {{ $company->email }}
                    </a>
                    @endif
                    @if($company->website)
                    <a href="{{ $company->website }}" target="_blank" class="flex items-center gap-3 text-sm text-blue-600 hover:text-blue-700 group break-all">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center group-hover:bg-indigo-100 transition flex-shrink-0">🌐</div>
                        {{ parse_url($company->website, PHP_URL_HOST) }}
                    </a>
                    @endif
                    @if($company->street_address)
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">📍</div>
                        <span>{{ collect([$company->street_address, $company->city, $company->county])->filter()->join(', ') }}</span>
                    </div>
                    @endif

                    @if(!$company->phone && !$company->email && !$company->website && !$company->street_address)
                        <p class="text-sm text-gray-400 text-center py-2">No contact info provided</p>
                    @endif
                </div>
            </div>

            {{-- Business details --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide">Business Details</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Type</dt>
                        <dd class="font-medium capitalize text-gray-800">{{ str_replace('_',' ', $company->business_type) }}</dd>
                    </div>
                    @if($company->registration_number)
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Reg. No.</dt>
                        <dd class="font-mono text-xs text-gray-700">{{ $company->registration_number }}</dd>
                    </div>
                    @endif
                    @if($company->registration_date)
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Registered</dt>
                        <dd class="text-gray-800">{{ $company->registration_date->format('d M Y') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Owner</dt>
                        <dd class="text-gray-800 font-medium">{{ $company->owner_name }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Listed</dt>
                        <dd class="text-gray-600 text-xs">{{ $company->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Feature this listing (owner only) --}}
            @auth
                @if($company->user_id === auth()->id() && !$company->is_featured)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-5"
                     x-data="{ phone: '{{ auth()->user()->phone_no ?? '' }}', paying: false }">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xl">⭐</span>
                        <h3 class="font-bold text-yellow-900">Feature This Listing</h3>
                    </div>
                    <p class="text-yellow-800 text-xs mb-4 leading-relaxed">
                        Get placed at the top of the directory and on the home page. <strong>KES 1,000/month</strong> via M-Pesa.
                    </p>
                    <input type="text" x-model="phone" placeholder="07xx xxx xxx"
                           class="w-full border border-yellow-300 bg-white rounded-xl px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                    <button @click="paying = true; submitPayment(phone, 1000, 'premium_listing', {{ $company->id }})"
                            :disabled="paying"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 disabled:opacity-60 text-white py-2.5 rounded-xl text-sm font-semibold transition">
                        <span x-show="!paying">Pay KES 1,000 via M-Pesa</span>
                        <span x-show="paying">📱 Check your phone…</span>
                    </button>
                </div>
                @endif
            @endauth
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
async function submitPayment(phone, amount, type, companyId) {
    const res = await fetch('{{ route('payments.mpesa') }}', {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ phone, amount, type, company_id: companyId })
    });
    if (!res.ok) alert('Payment initiation failed. Please try again.');
}
</script>
@endpush