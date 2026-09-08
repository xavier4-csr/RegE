@extends('layouts.app')
@section('title', 'Register Business')

@section('content')
<div class="max-w-3xl mx-auto" x-data="registerForm()">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">✎ Register Your Business</h1>
        <p class="text-gray-500 text-sm mt-1">Fields marked <span class="text-red-500">*</span> are required.</p>
    </div>

    {{-- AI Name Suggester --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
        <h2 class="font-semibold text-blue-900 mb-1">✨ Need a name? Let AI help</h2>
        <p class="text-sm text-blue-700 mb-3">Describe your business idea and get 6 unique name suggestions instantly.</p>
        <div class="flex gap-2">
            <input type="text" x-model="nameIdea"
                   placeholder="e.g. a mobile phone repair shop in Nairobi..."
                   class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <button @click="suggestNames()" :disabled="loadingNames"
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <span x-show="!loadingNames">Suggest →</span>
                <span x-show="loadingNames">Thinking…</span>
            </button>
        </div>

        <div x-show="suggestedNames.length > 0" class="mt-4 grid grid-cols-2 gap-2">
            <template x-for="n in suggestedNames" :key="n.name">
                <div class="bg-white border rounded-lg p-3 cursor-pointer hover:border-blue-400 transition"
                     :class="{'border-blue-500 bg-blue-50': selectedSuggestion === n.name}"
                     @click="selectName(n)">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900 text-sm" x-text="n.name"></span>
                        <span x-show="n.available" class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Available</span>
                        <span x-show="!n.available" class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Taken</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1" x-text="n.why"></p>
                </div>
            </template>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('my-companies.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Company Name <span class="text-red-500">*</span></label>
            <input type="text" name="company_name" id="company_name" required
                   value="{{ old('company_name') }}" x-model="companyName"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('company_name') border-red-400 @else border-gray-200 @enderror"
                   placeholder="e.g. Savannah Tech Solutions">
            @error('company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Owner Name <span class="text-red-500">*</span></label>
                <input type="text" name="owner_name" required value="{{ old('owner_name') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Business Type <span class="text-red-500">*</span></label>
                <select name="business_type" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select type…</option>
                    @foreach(['sole_proprietorship'=>'Sole Proprietorship','partnership'=>'Partnership','llc'=>'LLC','corporation'=>'Corporation','ngo'=>'NGO','cooperative'=>'Cooperative'] as $val => $label)
                        <option value="{{ $val }}" {{ old('business_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Industry Category</label>
            <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Select category…</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Street Address <span class="text-red-500">*</span></label>
            <input type="text" name="street_address" required value="{{ old('street_address') }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                   placeholder="123 Kenyatta Avenue">
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nairobi">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">County</label>
                <input type="text" name="county" value="{{ old('county') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nairobi County">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="00100">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="07xx xxx xxx">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="https://…">
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="text-sm font-semibold">Company Description</label>
                <button type="button" @click="writeDescription()"
                        :disabled="!companyName || loadingDesc"
                        class="text-xs text-blue-600 hover:underline disabled:opacity-40">
                    ✨ Write with AI
                </button>
            </div>
            <textarea name="company_description" id="company_description" rows="4"
                      x-model="description"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"
                      placeholder="Describe your business, products and services…">{{ old('company_description') }}</textarea>
            <p class="text-xs text-blue-600 mt-1" x-show="loadingDesc">Writing description…</p>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Logo (optional)</label>
            <input type="file" name="logo" accept="image/*"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP. Max 2MB.</p>
        </div>

        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" id="verify" name="verify" required class="w-4 h-4 accent-blue-600">
            <label for="verify" class="text-sm text-gray-600">
                I confirm this information is accurate and I am authorised to register this business.
            </label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold text-sm transition">
            ✎ Register Company
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function registerForm() {
    return {
        nameIdea: '',
        companyName: '',
        description: '',
        suggestedNames: [],
        selectedSuggestion: null,
        loadingNames: false,
        loadingDesc: false,

        async suggestNames() {
            if (!this.nameIdea.trim()) return;
            this.loadingNames = true;
            this.suggestedNames = [];
            try {
                const res = await fetch('{{ route('ai.names') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ idea: this.nameIdea }),
                });
                const data = await res.json();
                this.suggestedNames = data.names ?? [];
            } catch(e) { console.error(e); }
            this.loadingNames = false;
        },

        selectName(n) {
            this.selectedSuggestion = n.name;
            this.companyName = n.name;
            document.getElementById('company_name').value = n.name;
        },

        async writeDescription() {
            if (!this.companyName) return;
            this.loadingDesc = true;
            try {
                const res = await fetch('{{ route('ai.description') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        company_name: this.companyName,
                        business_type: document.querySelector('[name=business_type]')?.value || 'business',
                        industry: document.querySelector('[name=category_id] option:checked')?.text || '',
                        services: this.nameIdea || this.companyName,
                        location: document.querySelector('[name=city]')?.value,
                    }),
                });
                const data = await res.json();
                this.description = data.description ?? '';
                document.getElementById('company_description').value = this.description;
            } catch(e) { console.error(e); }
            this.loadingDesc = false;
        }
    }
}
</script>
@endpush
