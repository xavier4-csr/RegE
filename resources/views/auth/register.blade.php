@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-8">
<div class="w-full max-w-lg">

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
            <h1 class="text-2xl font-bold text-white">Create your account</h1>
            <p class="text-blue-200 text-sm mt-1">Join thousands of Kenyan entrepreneurs on RegE</p>
        </div>

        <div class="px-8 py-7">
            <form method="POST" action="{{ route('register') }}" class="space-y-4"
                  x-data="{ password: '', confirm: '', strength: 0,
                    get colour(){ return ['','bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'][this.strength] },
                    get label(){ return ['','Weak','Fair','Good','Strong ✓'][this.strength] },
                    check(){ let s=0; if(this.password.length>=8)s++; if(/[A-Z]/.test(this.password)&&/[a-z]/.test(this.password))s++; if(/\d/.test(this.password))s++; if(/[@$!%*?&#]/.test(this.password))s++; this.strength=s; } }">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required value="{{ old('first_name') }}"
                               class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('first_name') border-red-400 @else border-gray-200 @enderror"
                               placeholder="Amina">
                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required value="{{ old('last_name') }}"
                               class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('last_name') border-red-400 @else border-gray-200 @enderror"
                               placeholder="Ochieng">
                        @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-400 @else border-gray-200 @enderror"
                           placeholder="you@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_no" value="{{ old('phone_no') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="07xx xxx xxx">
                    <p class="text-xs text-gray-400 mt-1">Used for M-Pesa payments and SMS reminders</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required
                           x-model="password" @input="check()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="Min 8 characters">
                    <div class="mt-2 flex gap-1">
                        <template x-for="i in 4" :key="i">
                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                 :class="i <= strength ? colour : 'bg-gray-200'"></div>
                        </template>
                    </div>
                    <p class="text-xs mt-1 text-gray-400" x-text="label"></p>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           x-model="confirm"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           :class="confirm && password !== confirm ? 'border-red-400' : 'border-gray-200'">
                    <p x-show="confirm && password !== confirm" class="text-red-500 text-xs mt-1">
                        Passwords do not match
                    </p>
                </div>

                <div class="flex items-start gap-2 pt-1">
                    <input type="checkbox" id="terms" name="terms" required class="mt-0.5 w-4 h-4 accent-blue-600">
                    <label for="terms" class="text-xs text-gray-600 leading-relaxed">
                        I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Use</a>
                        and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    Create Account →
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
</div>
@endsection
