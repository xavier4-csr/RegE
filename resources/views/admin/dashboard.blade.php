@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="text-3xl font-bold text-blue-600">{{ number_format($totalUsers) }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Users</div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="text-3xl font-bold text-green-600">{{ number_format($totalCompanies) }}</div>
            <div class="text-sm text-gray-500 mt-1">Registered Companies</div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($totalPayments) }}</div>
            <div class="text-sm text-gray-500 mt-1">Completed Payments</div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="text-3xl font-bold text-orange-500">KES {{ number_format($totalRevenue) }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Revenue</div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Recent users --}}
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Users</h2>
                <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="divide-y">
                @foreach($recentUsers as $user)
                <div class="px-5 py-3 flex items-center gap-3">
                    <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ $user->full_name }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                    </div>
                    <span class="text-xs {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full">
                        {{ $user->role }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent companies --}}
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Companies</h2>
                <a href="{{ route('admin.companies') }}" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="divide-y">
                @foreach($recentCompanies as $company)
                <div class="px-5 py-3">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $company->company_name }}</div>
                    <div class="text-xs text-gray-400">{{ $company->user->full_name }} · {{ $company->county ?? 'Kenya' }}</div>
                    <span class="text-xs {{ $company->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-0.5 rounded-full mt-1 inline-block">
                        {{ $company->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent payments --}}
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Payments</h2>
                <a href="{{ route('admin.payments') }}" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="divide-y">
                @foreach($recentPayments as $payment)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->full_name }}</div>
                        <div class="text-xs text-gray-400 capitalize">{{ str_replace('_',' ', $payment->type) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">KES {{ number_format($payment->amount) }}</div>
                        <div class="text-xs text-green-600">Completed</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
