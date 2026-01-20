@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600 mt-2">Silakan pilih menu di bawah ini</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        
        {{-- CARD 1: AGENDA ADMIN (Selalu Muncul) --}}
        <a href="{{ route('events.admin_list') }}" class="group block bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 overflow-hidden">
            <div class="bg-blue-900 h-2"></div>
            <div class="p-8 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-blue-900 mb-4 group-hover:bg-blue-900 group-hover:text-white transition">
                    <i class="fas fa-calendar-alt text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Agenda Resmi</h3>
                <p class="text-gray-500">Lihat daftar agenda dan kegiatan yang diselenggarakan oleh Administrator/Kantor Pusat.</p>
                <span class="mt-6 text-blue-600 font-semibold group-hover:underline">Lihat Agenda &rarr;</span>
            </div>
        </a>

        {{-- CARD 2: BUAT AGENDA SENDIRI (Muncul HANYA JIKA Diberi Akses) --}}
        @if(Auth::user()->hasPermission('create_events'))
        <a href="{{ route('event.agenda') }}" class="group block bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 overflow-hidden">
            <div class="bg-green-600 h-2"></div>
            <div class="p-8 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-green-700 mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fas fa-edit text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Agenda Saya</h3>
                <p class="text-gray-500">Buat dan kelola agenda kegiatan Anda sendiri. Atur presensi dan QR Code secara mandiri.</p>
                <span class="mt-6 text-green-600 font-semibold group-hover:underline">Kelola Agenda &rarr;</span>
            </div>
        </a>
        @else
        {{-- CARD DISABLE (Opsional: Agar user tau fitur ini ada tapi terkunci) --}}
        <div class="block bg-gray-100 border border-gray-200 rounded-xl p-8 flex flex-col items-center text-center opacity-70 cursor-not-allowed">
            <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 mb-4">
                <i class="fas fa-lock text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-500 mb-2">Agenda Saya</h3>
            <p class="text-gray-500 text-sm">Fitur pembuatan agenda belum diaktifkan untuk akun Anda. Hubungi Admin untuk akses.</p>
        </div>
        @endif

    </div>
</div>
@endsection