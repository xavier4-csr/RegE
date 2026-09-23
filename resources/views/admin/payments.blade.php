@extends('admin.layout')
@section('title', 'Payments')

@section('content')
<div class="space-y-4">

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <div class="text-2xl font-bold text-green-600">KES {{ number_format($totalRevenue) }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Revenue</div>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $payments->total() }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Transactions</div>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <div class="text-2xl font-bold text-orange-500">
                KES {{ number_format($payments->where('status', 'pending')->sum('amount')) }}
            </div>
            <div class="text-sm text-gray-500 mt-1">Pending Amount</div>
        </div>
    </div>

    <form method="GET" class="flex gap-3">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All statuses</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
        </select>
        <select name="type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All types</option>
            <option value="registration_fee" {{ request('type') === 'registration_fee' ? 'selected' : '' }}>Registration Fee</option>
            <option value="premium_listing"  {{ request('type') === 'premium_listing'  ? 'selected' : '' }}>Premium Listing</option>
            <option value="document_fee"     {{ request('type') === 'document_fee'     ? 'selected' : '' }}>Document Fee</option>
            <option value="subscription"     {{ request('type') === 'subscription'     ? 'selected' : '' }}>Subscription</option>
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.payments') }}" class="border border-gray-200 px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Reset</a>
    </form>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Reference</th>
                        <th class="px-5 py-3 text-left">User</th>
                        <th class="px-5 py-3 text-left">Company</th>
                        <th class="px-5 py-3 text-left">Type</th>
                        <th class="px-5 py-3 text-left">Amount</th>
                        <th class="px-5 py-3 text-left">M-Pesa Receipt</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $payment->reference }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $payment->user->full_name }}</td>
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $payment->company?->company_name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600 capitalize text-xs">{{ str_replace('_',' ', $payment->type) }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-900">KES {{ number_format($payment->amount) }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $payment->mpesa_receipt ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700' : ($payment->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $payment->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-gray-400">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t">{{ $payments->links() }}</div>
    </div>
</div>
@endsection
