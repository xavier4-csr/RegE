@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <div class="text-center">
        <h1 class="text-3xl font-extrabold text-gray-900">📞 Contact Us</h1>
        <p class="text-gray-500 text-sm mt-2">We'd love to hear from you. Reach out any time.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Contact info --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">📍</span>
                    <div>
                        <p class="font-semibold text-gray-900">Our Office</p>
                        <p class="text-sm text-gray-500">Nairobi, Kenya</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">✉️</span>
                    <div>
                        <p class="font-semibold text-gray-900">Email</p>
                        <a href="mailto:support@rege.co.ke" class="text-sm text-blue-600 hover:underline">support@rege.co.ke</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">📱</span>
                    <div>
                        <p class="font-semibold text-gray-900">Phone</p>
                        <a href="tel:+254700000000" class="text-sm text-blue-600 hover:underline">+254 700 000 000</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact form --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">Send us a message</h2>
            <form method="POST" action="#" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Subject</label>
                    <input type="text" name="subject"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Message *</label>
                    <textarea name="message" rows="4" required
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm transition">
                    Send Message →
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
