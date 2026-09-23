@extends('admin.layout')
@section('title', 'Companies')

@section('content')
<div class="space-y-4">

    <form method="GET" class="flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by company name or county…"
               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All statuses</option>
            <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Search</button>
        <a href="{{ route('admin.companies') }}" class="border border-gray-200 px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Reset</a>
    </form>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b text-sm text-gray-500">{{ $companies->total() }} companies found</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Company</th>
                        <th class="px-5 py-3 text-left">Owner</th>
                        <th class="px-5 py-3 text-left">Type</th>
                        <th class="px-5 py-3 text-left">County</th>
                        <th class="px-5 py-3 text-left">Reviews</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Flags</th>
                        <th class="px-5 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($companies as $company)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $company->logo }}" class="w-8 h-8 rounded-lg object-cover">
                                <div class="font-medium text-gray-900 truncate max-w-32">{{ $company->company_name }}</div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $company->user->full_name }}</td>
                        <td class="px-5 py-3 text-gray-600 capitalize text-xs">{{ str_replace('_',' ', $company->business_type) }}</td>
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $company->county ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $company->reviews_count }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $company->status === 'active' ? 'bg-green-100 text-green-700' : ($company->status === 'suspended' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $company->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex gap-1">
                                @if($company->is_featured)
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded">⭐</span>
                                @endif
                                @if($company->is_verified)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded">✓</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-col gap-1">
                                <form method="POST" action="{{ route('admin.companies.toggle-status', $company) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs {{ $company->status === 'active' ? 'text-red-500' : 'text-green-600' }} hover:underline">
                                        {{ $company->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.companies.toggle-featured', $company) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-yellow-600 hover:underline">
                                        {{ $company->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.companies.toggle-verified', $company) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-blue-600 hover:underline">
                                        {{ $company->is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.companies.delete', $company) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($company->company_name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t">{{ $companies->links() }}</div>
    </div>
</div>
@endsection
