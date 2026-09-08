@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">👤 My Profile</h1>

    {{-- Profile photo + name --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6 flex flex-col sm:flex-row items-center gap-6">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}"
             class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 shadow">
        <div class="text-center sm:text-left flex-1">
            <h2 class="text-xl font-bold text-gray-900">{{ $user->full_name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <p class="text-gray-400 text-xs mt-1 capitalize">{{ $user->role }}</p>
        </div>
    </div>

    {{-- Update profile form --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Update Profile</h3>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('first_name') border-red-400 @enderror">
                    @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('last_name') border-red-400 @enderror">
                    @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition">
                Save Profile
            </button>
        </form>
    </div>

    {{-- Update photo --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Profile Photo</h3>
        <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="flex items-center gap-4">
            @csrf
            <input type="file" name="photo" accept="image/*" required
                   class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2">
            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition">
                Upload
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">JPG, PNG or WebP. Max 2MB.</p>
    </div>

    {{-- Change password --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Change Password</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Current Password *</label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">New Password *</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition">
                Update Password
            </button>
        </form>
    </div>

</div>
@endsection
