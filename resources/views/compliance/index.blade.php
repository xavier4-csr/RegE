@extends('layouts.app')
@section('title', 'Compliance — ' . $company->company_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-2">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                ← Back to Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">📋 Compliance Checklist</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $company->company_name }}</p>
        </div>
    </div>

    {{-- Progress summary --}}
    @php
        $total = $progress->count();
        $done  = ($progress['completed'] ?? collect())->count() + ($progress['skipped'] ?? collect())->count();
        $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
    @endphp
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="font-bold text-gray-900">{{ $pct }}% complete</p>
                <p class="text-sm text-gray-500">{{ $done }} of {{ $total }} steps done</p>
            </div>
            <div class="text-4xl">{{ $pct === 100 ? '🎉' : '📈' }}</div>
        </div>
        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-green-500 rounded-full transition-all" style="width: {{ $pct }}%"></div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
            {{ session('info') }}
        </div>
    @endif

    {{-- Steps grouped by status --}}
    @php
        $sections = [
            'pending'    => ['Pending', 'bg-yellow-50 border-yellow-200 text-yellow-800', '⏳'],
            'in_progress'=> ['In Progress', 'bg-blue-50 border-blue-200 text-blue-800', '🔵'],
            'completed'  => ['Completed', 'bg-green-50 border-green-200 text-green-800', '✅'],
            'skipped'    => ['Skipped', 'bg-gray-50 border-gray-200 text-gray-600', '⏭️'],
        ];
    @endphp

    @if($total === 0)
        <div class="bg-white border rounded-2xl p-10 text-center">
            <div class="text-5xl mb-3">🗂️</div>
            <p class="font-semibold text-gray-700">No compliance steps yet</p>
            <p class="text-sm text-gray-500 mt-1">This business type has no compliance steps configured.</p>
        </div>
    @else
        @foreach($sections as $key => [$label, $countClass, $icon])
            @php $items = $progress[$key] ?? collect(); @endphp
            @if($items->count())
            <section>
                <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span>{{ $icon }}</span> {{ $label }}
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $countClass }}">{{ $items->count() }}</span>
                </h2>
                <div class="space-y-3">
                    @foreach($items as $item)
                    <div class="bg-white border rounded-xl shadow-sm p-5 flex items-start gap-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->complianceStep->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $item->complianceStep->description }}</p>

                            <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">
                                <span>🏛️ {{ $item->complianceStep->authority }}</span>
                                @if($item->complianceStep->portal_url)
                                    <a href="{{ $item->complianceStep->portal_url }}" target="_blank"
                                       class="text-blue-600 hover:underline">Visit portal →</a>
                                @endif
                                @if($item->due_date)
                                    <span>📅 Due: {{ $item->due_date->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 shrink-0">
                            @if($item->status !== 'completed')
                                <form method="POST" action="{{ route('compliance.complete', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="w-full text-xs bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                        ✓ Mark Complete
                                    </button>
                                </form>
                            @endif
                            @if($item->status === 'pending')
                                <form method="POST" action="{{ route('compliance.skip', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="w-full text-xs border border-gray-200 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                        Skip
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        @endforeach
    @endif

</div>
@endsection
