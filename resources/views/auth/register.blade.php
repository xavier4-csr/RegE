@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-10">
<div class="w-full max-w-lg">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl shadow-lg mb-4">
            <span class="text-white font-extrabold text-xl">R</span>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900">Create your account</h1>
        <p class="text-gray-500 text-sm mt-1">Join Kenyan entrepreneurs on RegE — free to get started</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <form method="POST" action="{{ route('register') }}" class="space-y-5"
              x-data="{
                password: '', confirm: '', strength: 0,
                get colour(){ return ['bg-gray-200','bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'][this.strength] },
                get label(){ return ['','Weak — add uppercase & symbols','Fair','Good','Strong ✓'][this.strength] },
                get labelColour(){ return ['text-gray-400','text-red-500','text-orange-500','text-yellow-600','text-green-600'][this.strength] },
                check(){
                    let s = 0;
                    if (this.password.length >= 8) s++;
                    if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) s++;
                    if (/\d/.test(this.password)) s++;
                    if (/[@$!%*?&#^]/.test(this.password)) s++;
                    this.strength = s;
                }
              }">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">First Name *</label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                           class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('first_name') border-red-400 @else border-gray-200 @enderror"
                           placeholder="Amina">
                    @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name *</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                           class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('last_name') border-red-400 @else border-gray-200 @enderror"
                           placeholder="Ochieng">
                    @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address *</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-400 @else border-gray-200 @enderror"
                           placeholder="you@example.com">
                </div>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">🇰🇪</span>
                    <input type="text" name="phone_no" value="{{ old('phone_no') }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="07xx xxx xxx">
                </div>
                <p class="text-xs text-gray-400 mt-1">Used for M-Pesa payments and SMS compliance reminders</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password *</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input type="password" name="password" required
                           x-model="password" @input="check()"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="Min 8 characters">
                </div>
                {{-- Strength bar --}}
                <div class="mt-2 flex gap-1">
                    <template x-for="i in 4" :key="i">
                        <div class="h-1.5 flex-1 rounded-full transition-colors duration-300"
                             :class="i <= strength ? colour : 'bg-gray-200'"></div>
                    </template>
                </div>
                <p class="text-xs mt-1 transition-colors" :class="labelColour" x-text="label"></p>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password *</label>
                <input type="password" name="password_confirmation" required x-model="confirm"
                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                       :class="confirm && password !== confirm ? 'border-red-400 bg-red-50' : 'border-gray-200'"
                       placeholder="Repeat your password">
                <p x-show="confirm && password !== confirm" class="text-red-500 text-xs mt-1">
                    Passwords do not match
                </p>
            </div>

            <div class="flex items-start gap-2.5 pt-1">
                <input type="checkbox" id="terms" name="terms" required
                       class="mt-0.5 w-4 h-4 accent-blue-600 rounded flex-shrink-0">
                <label for="terms" class="text-xs text-gray-600 leading-relaxed">
                    I agree to the <a href="#" class="text-blue-600 font-medium hover:underline">Terms of Use</a>
                    and <a href="#" class="text-blue-600 font-medium hover:underline">Privacy Policy</a>.
                    I understand RegE provides guidance and is not a substitute for legal advice.
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-sm shadow-blue-200">
                Create Account →
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign in</a>
            </p>
        </div>
    </div>
</div>
</div>
@endsection