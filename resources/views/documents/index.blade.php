@extends('layouts.app')
@section('title', 'Documents')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">📄 Document Hub</h1>
            <p class="text-gray-500 text-sm mt-0.5">Generate and download business documents</p>
        </div>
    </div>

    {{-- Generate documents --}}
    @if($companies->count() > 0)
    <div class="grid md:grid-cols-3 gap-5">

        {{-- Registration Certificate --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl mb-4">📋</div>
            <h3 class="font-bold text-gray-900 mb-1">Registration Summary</h3>
            <p class="text-gray-500 text-xs mb-4 leading-relaxed">
                A professional PDF summary of your business registration details, type, owner, address, and compliance progress.
            </p>
            <form method="GET" action="">
                <select name="company_id" id="cert-company"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($companies as $co)
                        <option value="{{ $co->id }}">{{ $co->company_name }}</option>
                    @endforeach
                </select>
                <button type="button"
                        onclick="window.location.href='{{ url('documents') }}/' + document.getElementById('cert-company').value + '/registration-cert'"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl text-sm font-semibold transition">
                    Download PDF
                </button>
            </form>
        </div>

        {{-- Compliance Checklist --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl mb-4">✅</div>
            <h3 class="font-bold text-gray-900 mb-1">Compliance Checklist</h3>
            <p class="text-gray-500 text-xs mb-4 leading-relaxed">
                A detailed PDF of all your post-registration compliance steps with their status, due dates, and government portal links.
            </p>
            <form method="GET" action="">
                <select name="company_id" id="checklist-company"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($companies as $co)
                        <option value="{{ $co->id }}">{{ $co->company_name }}</option>
                    @endforeach
                </select>
                <button type="button"
                        onclick="window.location.href='{{ url('documents') }}/' + document.getElementById('checklist-company').value + '/compliance-checklist'"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-xl text-sm font-semibold transition">
                    Download PDF
                </button>
            </form>
        </div>

        {{-- Partnership Deed --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition"
             x-data="{ open: false }">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-2xl mb-4">🤝</div>
            <h3 class="font-bold text-gray-900 mb-1">Partnership Deed</h3>
            <p class="text-gray-500 text-xs mb-4 leading-relaxed">
                A template partnership agreement covering profit sharing, management, dissolution, and dispute resolution under Kenyan law.
            </p>
            <button @click="open = true"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-xl text-sm font-semibold transition">
                Generate Deed
            </button>

            {{-- Partnership deed modal --}}
            <div x-show="open" x-transition
                 class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="font-bold text-gray-900">Generate Partnership Deed</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                        </div>

                        <form method="POST" action="" id="deedForm" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold mb-1">Select Company *</label>
                                <select name="company_id" id="deed-company" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                        onchange="updateDeedAction(this.value)">
                                    @foreach($companies as $co)
                                        <option value="{{ $co->id }}">{{ $co->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Business Address *</label>
                                <input type="text" name="business_address" required
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                       placeholder="e.g. 123 Kenyatta Ave, Nairobi">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Commencement Date *</label>
                                <input type="date" name="commencement_date" required
                                       value="{{ today()->format('Y-m-d') }}"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div x-data="{ partners: [{name:'',id_no:'',share:50},{name:'',id_no:'',share:50}] }">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-semibold">Partners (minimum 2) *</label>
                                    <button type="button" @click="partners.push({name:'',id_no:'',share:0})"
                                            class="text-xs text-blue-600 hover:underline">+ Add partner</button>
                                </div>
                                <template x-for="(p, i) in partners" :key="i">
                                    <div class="bg-gray-50 rounded-lg p-3 mb-2 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-gray-600" x-text="'Partner ' + (i+1)"></span>
                                            <button type="button" x-show="partners.length > 2"
                                                    @click="partners.splice(i,1)"
                                                    class="text-xs text-red-500 hover:underline">Remove</button>
                                        </div>
                                        <input type="text" :name="'partners['+i+'][name]'" x-model="p.name" required
                                               placeholder="Full name"
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="text" :name="'partners['+i+'][id_no]'" x-model="p.id_no" required
                                                   placeholder="ID / Passport No."
                                                   class="border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                                            <div class="relative">
                                                <input type="number" :name="'partners['+i+'][share]'" x-model="p.share" required
                                                       min="1" max="100" placeholder="Share %"
                                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <p class="text-xs text-gray-400 mt-1">Shares must total 100%</p>
                            </div>

                            <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2.5 rounded-xl text-sm font-semibold transition">
                                Generate &amp; Download Deed
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center">
        <div class="text-4xl mb-3">🏢</div>
        <p class="font-semibold text-gray-700 mb-2">No companies registered yet</p>
        <a href="{{ route('my-companies.create') }}"
           class="inline-block bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            Register your first business →
        </a>
    </div>
    @endif

    {{-- Previously generated documents --}}
    @if($documents->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h2 class="font-bold text-gray-900">Previously Generated</h2>
        </div>
        <div class="divide-y">
            @foreach($documents as $doc)
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center text-lg flex-shrink-0">📄</div>
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $doc->title }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ $doc->company?->company_name }} ·
                            {{ $doc->created_at->format('d M Y') }} ·
                            {{ $doc->file_size_human }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('documents.download', $doc) }}"
                       class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition">
                        Download
                    </a>
                    <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                          onsubmit="return confirm('Delete this document?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-500 hover:text-red-600 px-2 py-1.5">✕</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function updateDeedAction(companyId) {
    document.getElementById('deedForm').action = '/documents/' + companyId + '/partnership-deed';
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('deed-company');
    if (sel) updateDeedAction(sel.value);
});
</script>
@endpush