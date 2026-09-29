@extends('layouts.app')
@section('title', 'Compliance — ' . $company->company_name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Back + header --}}
    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Dashboard
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900">📋 Compliance Tracker</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ $company->company_name }}</p>
        </div>
        <a href="{{ route('companies.show', $company) }}"
           class="text-xs text-gray-500 hover:text-blue-600 border border-gray-200 px-3 py-1.5 rounded-lg hover:border-blue-300 transition mt-8">
            View profile →
        </a>
    </div>

    {{-- Overall progress --}}
    @php
        $total = $company->complianceProgress()->count();
        $done  = $company->complianceProgress()->where('status','completed')->count();
        $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
        $color = $pct >= 100 ? 'bg-green-500' : ($pct >= 50 ? 'bg-blue-500' : 'bg-amber-400');
    @endphp
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="font-bold text-gray-900 text-lg">Overall Progress</div>
                <div class="text-sm text-gray-500 mt-0.5">{{ $done }} of {{ $total }} steps completed</div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-extrabold {{ $pct >= 100 ? 'text-green-600' : ($pct >= 50 ? 'text-blue-600' : 'text-amber-500') }}">
                    {{ $pct }}%
                </div>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3">
            <div class="{{ $color }} h-3 rounded-full transition-all duration-700"
                 style="width:{{ $pct }}%"></div>
        </div>
        @if($pct >= 100)
            <div class="mt-3 flex items-center gap-2 text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5 text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                All compliance steps complete — your business is fully compliant! 🎉
            </div>
        @endif
    </div>

    {{-- AI Compliance Guide --}}
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5"
         x-data="{ question:'', answer:'', loading:false }">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-sm font-bold">AI</div>
            <div>
                <h2 class="font-bold text-blue-900">Compliance Guide</h2>
                <p class="text-sm text-blue-700">Ask anything about your post-registration obligations in Kenya</p>
            </div>
        </div>
        <div class="flex gap-2">
            <input type="text" x-model="question"
                   placeholder="e.g. When do I need to register for VAT?"
                   class="flex-1 border border-blue-200 bg-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                   @keydown.enter="askGuide()">
            <button @click="askGuide()" :disabled="!question || loading"
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition flex-shrink-0">
                <span x-show="!loading">Ask →</span>
                <span x-show="loading" class="flex items-center gap-1">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Thinking
                </span>
            </button>
        </div>
        <div x-show="answer"
             class="mt-4 bg-white rounded-xl p-4 border border-blue-100 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap shadow-sm"
             x-text="answer"></div>
    </div>

    {{-- Steps by status --}}
    @php
        $statusConfig = [
            'pending'     => ['⏳ Pending',     'amber',  true],
            'in_progress' => ['🔄 In Progress', 'blue',   true],
            'completed'   => ['✅ Completed',   'green',  false],
            'skipped'     => ['⏭ Skipped',     'gray',   false],
        ];
    @endphp

    @foreach($statusConfig as $status => [$label, $colour, $showActions])
        @if(isset($progress[$status]) && $progress[$status]->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b bg-{{ $colour }}-50 flex items-center justify-between">
                <h2 class="font-bold text-{{ $colour }}-800 text-sm">{{ $label }}</h2>
                <span class="text-xs bg-{{ $colour }}-100 text-{{ $colour }}-700 px-2 py-0.5 rounded-full font-medium">
                    {{ $progress[$status]->count() }}
                </span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($progress[$status] as $item)
                <div class="px-5 py-4 flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                {{ $status === 'completed' ? '✓' : ($status === 'skipped' ? '—' : '○') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 text-sm">{{ $item->complianceStep->title }}</div>
                                <div class="text-xs text-blue-600 font-medium mt-0.5">{{ $item->complianceStep->authority }}</div>
                                <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">{{ $item->complianceStep->description }}</p>

                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    @if($item->complianceStep->portal_url)
                                        <a href="{{ $item->complianceStep->portal_url }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Open portal
                                        </a>
                                    @endif
                                    @if($item->due_date)
                                        <span class="inline-flex items-center gap-1 text-xs {{ $item->due_date->isPast() && $status === 'pending' ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $item->due_date->format('d M Y') }} ({{ $item->due_date->diffForHumans() }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($showActions)
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('compliance.complete', $item) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg font-medium transition w-full">
                                Mark done ✓
                            </button>
                        </form>
                        <form method="POST" action="{{ route('compliance.skip', $item) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs border border-gray-200 hover:bg-gray-50 text-gray-600 px-4 py-1.5 rounded-lg transition w-full">
                                Skip
                            </button>
                        </form>
                    </div>
                    @elseif($status === 'completed')
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('complianceGuide', () => ({
        question: '', answer: '', loading: false,
        async askGuide() {
            if (!this.question) return;
            this.loading = true; this.answer = '';
            try {
                const r = await fetch('{{ route('ai.compliance') }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ question: this.question, business_type: '{{ $company->business_type }}', county: '{{ $company->county ?? 'Nairobi' }}', has_employees: false })
                });
                const d = await r.json();
                this.answer = d.answer ?? 'No answer received.';
            } catch(e) { this.answer = 'Something went wrong. Try again.'; }
            this.loading = false;
        }
    }));
});
</script>
@endpush