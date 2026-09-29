@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-7">

    {{-- Welcome header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
                {{ auth()->user()->first_name }} 👋
            </h1>
            <p class="text-gray-500 text-sm mt-0.5">Here's an overview of your businesses and compliance.</p>
        </div>
        <a href="{{ route('my-companies.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shadow-blue-200 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Register Business
        </a>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['Companies', $companies->count(), 'text-blue-600', 'bg-blue-50', '🏢'],
            ['Compliance Due', $pendingCompliance->count(), 'text-amber-600', 'bg-amber-50', '⏰'],
            ['Profile Views', number_format($companies->sum('profile_views')), 'text-purple-600', 'bg-purple-50', '👁'],
            ['Notifications', $unreadCount, 'text-rose-600', 'bg-rose-50', '🔔'],
        ] as [$label, $value, $text, $bg, $icon])
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-3xl font-extrabold {{ $text }}">{{ $value }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $label }}</div>
                </div>
                <div class="{{ $bg }} w-10 h-10 rounded-xl flex items-center justify-center text-xl">{{ $icon }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Compliance alerts --}}
    @if($pendingCompliance->count() > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">⏰</div>
            <h2 class="font-bold text-amber-900">Compliance Actions Due Soon</h2>
        </div>
        <div class="space-y-2">
            @foreach($pendingCompliance as $item)
            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-amber-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full {{ $item->due_date->isPast() ? 'bg-red-500' : 'bg-amber-400' }}"></div>
                    <div>
                        <span class="font-medium text-gray-800 text-sm">{{ $item->complianceStep->title }}</span>
                        <span class="text-gray-400 text-xs ml-2">{{ $item->company->company_name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm {{ $item->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ $item->due_date->diffForHumans() }}
                    </span>
                    <a href="{{ route('compliance.index', $item->company->id) }}"
                       class="text-xs bg-amber-100 hover:bg-amber-200 text-amber-800 px-3 py-1 rounded-lg font-medium transition">
                        View →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Companies table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Your Companies</h2>
            <span class="text-xs text-gray-400">{{ $companies->count() }} total</span>
        </div>

        @if($companies->isEmpty())
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">🏢</div>
            <p class="font-semibold text-gray-700 mb-1">No businesses registered yet</p>
            <p class="text-sm text-gray-400 mb-5">Register your first business to get started</p>
            <a href="{{ route('my-companies.create') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                Register your first business →
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">County</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Compliance</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($companies as $company)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $company->logo }}"
                                     class="w-9 h-9 rounded-xl object-cover border border-gray-200 flex-shrink-0">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $company->company_name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $company->owner_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 capitalize text-xs">
                            {{ str_replace('_', ' ', $company->business_type) }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $company->county ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all
                                        {{ $company->compliance_percent >= 100 ? 'bg-green-500' : ($company->compliance_percent >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                         style="width:{{ $company->compliance_percent }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-500">{{ $company->compliance_percent }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs font-medium">
                            {{ number_format($company->profile_views) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $company->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $company->status === 'active' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('companies.show', $company) }}"
                                   class="text-xs text-blue-600 hover:text-blue-700 font-medium">View</a>
                                <a href="{{ route('my-companies.edit', $company) }}"
                                   class="text-xs text-gray-600 hover:text-gray-800 font-medium">Edit</a>
                                <a href="{{ route('compliance.index', $company->id) }}"
                                   class="text-xs text-amber-600 hover:text-amber-700 font-medium">Compliance</a>
                                <form method="POST" action="{{ route('my-companies.destroy', $company) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($company->company_name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection