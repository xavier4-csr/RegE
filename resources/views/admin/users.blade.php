@extends('admin.layout')
@section('title', 'Users')

@section('content')
<div class="space-y-4">

    {{-- Search --}}
    <form method="GET" class="flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or email…"
               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All roles</option>
            <option value="owner"  {{ request('role') === 'owner'  ? 'selected' : '' }}>Owner</option>
            <option value="admin"  {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Search</button>
        <a href="{{ route('admin.users') }}" class="border border-gray-200 px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Reset</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b text-sm text-gray-500">
            {{ $users->total() }} users found
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">User</th>
                        <th class="px-5 py-3 text-left">Phone</th>
                        <th class="px-5 py-3 text-left">Role</th>
                        <th class="px-5 py-3 text-left">Companies</th>
                        <th class="px-5 py-3 text-left">Joined</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->full_name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->phone_no ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->companies_count }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs {{ $user->is_active ? 'text-red-500' : 'text-green-600' }} hover:underline">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.make-admin', $user) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-purple-600 hover:underline">
                                        {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($user->full_name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:underline">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t">{{ $users->links() }}</div>
    </div>
</div>
@endsection
