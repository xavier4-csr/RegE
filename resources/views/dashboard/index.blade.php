@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Welcome back, {{ $user->first_name }} 👋
            </h1>
            <p class="text-gray-500 text-sm mt-1">Here's an overview of your businesses and compliance.</p>
        </div>
        <a href="{{ route('my-companies.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition">
            + Register Business
        </a>
    </div>

    {{-- My companies --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">🏢 My Businesses</h2>
            <a href="{{ route('companies.index') }}" class="text-sm text-blue-600 hover:underline">View directory →</a>
        </div>

        @if($companies->isEmpty())
            <div class="bg-white border rounded-2xl p-10 text-center">
                <div class="text-5xl mb-3">🏢</div>
                <p class="font-semibold text-gray-700">You haven't registered any businesses yet</p>
                <p class="text-sm text-gray-500 mt-1">Register your first business to get started with compliance tracking.</p>
                <a href="{{ route('my-companies.create') }}"
                   class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                    Register your first business →
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($companies as $company)
                <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600 relative">
                        <img src="{{ $company->logo }}" alt=""
                             class="w-14 h-14 rounded-xl object-cover border-2 border-white shadow absolute bottom-0 left-4 translate-y-1/2 bg-white">
                    </div>
                    <div class="p-4 pt-10">
                        <h3 class="font-bold text-gray-900 truncate">{{ $company->company_name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $company->reviews_count }} reviews · {{ $company->compliance_percent }}% compliant
                        </p>
                        <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full"
                                 style="width: {{ $company->compliance_percent }}%"></div>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('companies.show', $company) }}"
                               class="flex-1 text-center text-xs bg-blue-600 text-white py-1.5 rounded-lg hover:bg-blue-700 transition">
                                View
                            </a>
                            <a href="{{ route('compliance.index', $company->id) }}"
                               class="flex-1 text-center text-xs border border-gray-200 text-gray-700 py-1.5 rounded-lg hover:bg-gray-50 transition">
                                Compliance
                            </a>
                            <a href="{{ route('my-companies.edit', $company) }}"
                               class="flex-1 text-center text-xs border border-gray-200 text-gray-700 py-1.5 rounded-lg hover:bg-gray-50 transition">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Pending compliance + payments --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Upcoming compliance --}}
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-4">⏰ Upcoming Compliance</h2>
            @if($pendingCompliance->isEmpty())
                <div class="bg-white border rounded-xl p-8 text-center">
                    <div class="text-4xl mb-2">✅</div>
                    <p class="text-sm text-gray-500">All caught up! No compliance items due soon.</p>
                </div>
            @else
                <div class="bg-white border rounded-xl divide-y">
                    @foreach($pendingCompliance as $item)
                    <div class="p-4 flex items-start gap-3">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">{{ $item->complianceStep->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $item->company->company_name }}
                                @if($item->due_date)
                                    · Due {{ $item->due_date->format('d M') }}
                                @endif
                            </p>
                        </div>
                       <a href="{{ route('compliance.index', $item->company->id) }}"
                           class="text-xs bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition whitespace-nowrap">
                            Manage →
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Recent payments --}}
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-4">💳 Recent Payments</h2>
            @if($recentPayments->isEmpty())
                <div class="bg-white border rounded-xl p-8 text-center">
                    <div class="text-4xl mb-2">💳</div>
                    <p class="text-sm text-gray-500">No payments yet.</p>
                </div>
            @else
                <div class="bg-white border rounded-xl divide-y">
                    @foreach($recentPayments as $payment)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm capitalize">
                                {{ str_replace('_',' ', $payment->type) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">KES {{ number_format($payment->amount) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Completed</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

</div>
@endsection
