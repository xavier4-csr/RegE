@extends('layouts.app')
@section('title', $company->company_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('companies.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:underline">
        ← Back to Directory
    </a>

    {{-- Hero card --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        <div class="px-6 pb-6">
            <div class="flex items-end justify-between -mt-10 mb-4">
                <img src="{{ $company->logo }}" alt="{{ $company->company_name }}"
                     class="w-20 h-20 rounded-2xl border-4 border-white shadow-md object-cover bg-white">
                <div class="flex gap-2 mt-10">
                    @if($company->is_verified)
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">✓ Verified</span>
                    @endif
                    @if($company->is_featured)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">⭐ Featured</span>
                    @endif
                    @auth
                        @if($company->user_id === auth()->id())
                            <a href="{{ route('my-companies.edit', $company) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full transition">
                                ✏️ Edit
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900">{{ $company->company_name }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-500">
                @if($company->category)
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                        {{ $company->category->name }}
                    </span>
                @endif
                <span>{{ ucfirst(str_replace('_',' ', $company->business_type)) }}</span>
                @if($company->city || $company->county)
                    <span>📍 {{ collect([$company->city, $company->county])->filter()->join(', ') }}</span>
                @endif
                <span>👁 {{ number_format($company->profile_views) }} views</span>
                @if($company->review_count > 0)
                    <span>★ {{ $company->average_rating }} ({{ $company->review_count }} reviews)</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Left: description + reviews --}}
        <div class="md:col-span-2 space-y-5">

            @if($company->company_description)
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="font-bold text-gray-900 mb-2">About</h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $company->company_description }}</p>
            </div>
            @endif

            <div class="bg-white rounded-xl border shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900">Reviews</h2>
                </div>

                @if($company->reviews->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-6">No reviews yet. Be the first!</p>
                @else
                    <div class="space-y-4">
                        @foreach($company->reviews as $review)
                        <div class="border-b pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $review->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                                    <span class="text-sm font-semibold text-gray-800">{{ $review->user->full_name }}</span>
                                </div>
                                <div class="text-yellow-400 text-sm">
                                    @for($i=1;$i<=5;$i++) {{ $i <= $review->rating ? '★' : '☆' }} @endfor
                                </div>
                            </div>
                            @if($review->title)
                                <p class="text-sm font-medium text-gray-800">{{ $review->title }}</p>
                            @endif
                            @if($review->body)
                                <p class="text-sm text-gray-600 mt-0.5">{{ $review->body }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Review form --}}
                @auth
                    @if($company->user_id !== auth()->id())
                    <div class="mt-6 pt-5 border-t" x-data="{ rating: 0 }">
                        <h3 class="font-semibold text-gray-900 mb-3">Leave a Review</h3>
                        <form method="POST" action="{{ route('reviews.store', $company) }}" class="space-y-3">
                            @csrf
                            <div class="flex gap-1">
                                @for($i=1;$i<=5;$i++)
                                <button type="button" @click="rating = {{ $i }}"
                                        class="text-2xl transition"
                                        :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                            <input type="text" name="title" placeholder="Review title (optional)"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <textarea name="body" rows="3" placeholder="Share your experience…"
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                                Submit Review
                            </button>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Right: contact + details --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border shadow-sm p-5 space-y-3">
                <h2 class="font-bold text-gray-900">Contact</h2>
                @if($company->phone)
                    <a href="tel:{{ $company->phone }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600">
                        📞 {{ $company->phone }}
                    </a>
                @endif
                @if($company->email)
                    <a href="mailto:{{ $company->email }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 break-all">
                        ✉️ {{ $company->email }}
                    </a>
                @endif
                @if($company->website)
                    <a href="{{ $company->website }}" target="_blank"
                       class="flex items-center gap-2 text-sm text-blue-600 hover:underline break-all">
                        🌐 {{ parse_url($company->website, PHP_URL_HOST) }}
                    </a>
                @endif
                @if($company->street_address)
                    <p class="flex items-start gap-2 text-sm text-gray-600">
                        📍 {{ collect([$company->street_address, $company->city, $company->county])->filter()->join(', ') }}
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="font-bold text-gray-900 mb-3">Details</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Type</dt>
                        <dd class="font-medium capitalize">{{ str_replace('_',' ', $company->business_type) }}</dd>
                    </div>
                    @if($company->registration_number)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Reg. No.</dt>
                        <dd class="font-mono text-xs">{{ $company->registration_number }}</dd>
                    </div>
                    @endif
                    @if($company->registration_date)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Registered</dt>
                        <dd>{{ $company->registration_date->format('d M Y') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Owner</dt>
                        <dd>{{ $company->owner_name }}</dd>
                    </div>
                </dl>
            </div>

            @auth
                @if((int) $company->user_id === (int) auth()->id() && !$company->is_featured)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                        <h2 class="font-bold text-yellow-900">Get Featured</h2>
                        <p class="text-sm text-yellow-800 mt-1 mb-4">Test an M-Pesa STK Push for this company.</p>
                        <form method="POST" action="{{ route('payments.mpesa') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="amount" value="1">
                            <input type="hidden" name="type" value="premium_listing">
                            <input type="hidden" name="company_id" value="{{ $company->id }}">
                            <label class="block text-sm font-medium text-yellow-900" for="payment-phone">M-Pesa phone number</label>
                            <input id="payment-phone" name="phone" type="tel" value="{{ auth()->user()->phone_no }}" required
                                   placeholder="0712345678"
                                   class="w-full border border-yellow-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 outline-none">
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                Send STK Push
                            </button>
                        </form>
                    </div>
                @elseif((int) $company->user_id !== (int) auth()->id())
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <h2 class="font-bold text-gray-900">Get Featured</h2>
                        <p class="text-sm text-gray-600 mt-1">Only the company owner can start a featured listing payment.</p>
                    </div>
                @endif
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                    <h2 class="font-bold text-gray-900">Get Featured</h2>
                    <p class="text-sm text-gray-600 mt-1">Log in as the company owner to test the M-Pesa STK Push.</p>
                </div>
            @endauth
        </div>
    </div>

</div>
@endsection
