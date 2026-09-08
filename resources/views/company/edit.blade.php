@extends('layouts.app')
@section('title', 'Edit — ' . $company->company_name)

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Company</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $company->company_name }}</p>
        </div>
        <a href="{{ route('companies.show', $company) }}" class="text-sm text-blue-600 hover:underline">
            View public profile →
        </a>
    </div>

    <form method="POST" action="{{ route('my-companies.update', $company) }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Logo preview --}}
        <div class="flex items-center gap-4">
            <img id="logoPreview" src="{{ $company->logo }}"
                 class="w-16 h-16 rounded-xl object-cover border shadow-sm">
            <div>
                <label class="block text-sm font-semibold mb-1">Company Logo</label>
                <input type="file" name="logo" id="logoInput" accept="image/*"
                       class="text-sm" onchange="previewLogo(event)">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP. Max 2MB.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Company Name *</label>
                <input type="text" name="company_name" required
                       value="{{ old('company_name', $company->company_name) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('company_name') border-red-400 @enderror">
                @error('company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Owner Name *</label>
                <input type="text" name="owner_name" required
                       value="{{ old('owner_name', $company->owner_name) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Business Type *</label>
                <select name="business_type"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach(['sole_proprietorship'=>'Sole Proprietorship','partnership'=>'Partnership','llc'=>'LLC','corporation'=>'Corporation','ngo'=>'NGO','cooperative'=>'Cooperative'] as $val => $label)
                        <option value="{{ $val }}" {{ old('business_type', $company->business_type) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Industry Category</label>
                <select name="category_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select…</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $company->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Street Address *</label>
            <input type="text" name="street_address" required
                   value="{{ old('street_address', $company->street_address) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1">City</label>
                <input type="text" name="city" value="{{ old('city', $company->city) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">County</label>
                <input type="text" name="county" value="{{ old('county', $company->county) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $company->website) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="https://…">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Company Description</label>
            <textarea name="company_description" rows="4"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('company_description', $company->company_description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">BRS Registration Number</label>
                <input type="text" name="registration_number"
                       value="{{ old('registration_number', $company->registration_number) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                       placeholder="e.g. BN/2024/123456">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Date of Registration</label>
                <input type="date" name="registration_date"
                       value="{{ old('registration_date', $company->registration_date?->format('Y-m-d')) }}"
                       max="{{ today()->format('Y-m-d') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm transition">
                Save Changes
            </button>
            <a href="{{ route('dashboard') }}"
               class="flex-1 text-center border border-gray-200 hover:bg-gray-50 text-gray-700 py-2.5 rounded-lg text-sm transition">
                Cancel
            </a>
        </div>
    </form>

    {{-- Danger zone --}}
    <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-5">
        <h3 class="font-bold text-red-800 mb-2">⚠ Danger Zone</h3>
        <p class="text-sm text-red-700 mb-3">
            Deleting this company removes it from the directory and all compliance tracking.
        </p>
        <form method="POST" action="{{ route('my-companies.destroy', $company) }}"
              onsubmit="return confirm('Delete {{ addslashes($company->company_name) }}? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                Delete Company
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('logoPreview').src = e.target.result;
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
