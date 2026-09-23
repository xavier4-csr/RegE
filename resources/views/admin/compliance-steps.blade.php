@extends('admin.layout')
@section('title', 'Compliance Steps')

@section('content')
<div class="grid md:grid-cols-2 gap-6">

    {{-- Existing steps --}}
    <div class="space-y-3">
        <h2 class="font-semibold text-gray-900">Current Steps ({{ $steps->count() }})</h2>

        @forelse($steps as $step)
        <div class="bg-white rounded-xl border shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-mono">#{{ $step->sort_order }}</span>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $step->title }}</h3>
                        @if($step->is_mandatory)
                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Mandatory</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Optional</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $step->authority }}</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $step->description }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                        <span>Due: Day {{ $step->days_after_registration }}</span>
                        @if($step->portal_url)
                            <a href="{{ $step->portal_url }}" target="_blank" class="text-blue-500 hover:underline">Portal →</a>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.compliance-steps.delete', $step) }}"
                      onsubmit="return confirm('Delete this step? This will remove it from all company checklists.')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 hover:text-red-600 text-xs">✕</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
            No compliance steps yet.
        </div>
        @endforelse
    </div>

    {{-- Add new step --}}
    <div>
        <h2 class="font-semibold text-gray-900 mb-3">Add New Step</h2>
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <form method="POST" action="{{ route('admin.compliance-steps.create') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="e.g. Register for VAT">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Description *</label>
                    <textarea name="description" required rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"
                              placeholder="What does this step involve?">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Authority *</label>
                    <input type="text" name="authority" required value="{{ old('authority') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="e.g. Kenya Revenue Authority (KRA)">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Portal URL</label>
                    <input type="url" name="portal_url" value="{{ old('portal_url') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           placeholder="https://…">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Days after registration</label>
                        <input type="number" name="days_after_registration" required min="0"
                               value="{{ old('days_after_registration', 0) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Sort order</label>
                        <input type="number" name="sort_order" min="0"
                               value="{{ old('sort_order', $steps->count() + 1) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_mandatory" id="is_mandatory" value="1"
                           {{ old('is_mandatory', true) ? 'checked' : '' }}
                           class="w-4 h-4 accent-blue-600">
                    <label for="is_mandatory" class="text-sm text-gray-700">Mandatory step</label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm transition">
                    Add Compliance Step
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
