@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600 mt-2">Kelola Agenda Kegiatan Anda</p>
    </div>

    {{-- Ubah Grid menjadi Flex Center agar kartu berada di tengah --}}
    <div class="flex justify-center max-w-4xl mx-auto">
        
        {{-- KARTU AGENDA RESMI DIHAPUS DISINI --}}

        {{-- CARD: AGENDA SAYA (Sekarang menjadi satu-satunya menu) --}}
        @if(Auth::user()->hasPermission('create_events'))
        {{-- Tambahkan w-full dan max-w-md agar ukuran kartu pas --}}
        <a href="{{ route('event.agenda') }}" class="w-full max-w-lg group block bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 overflow-hidden">
            <div class="bg-green-600 h-2"></div>
            <div class="p-8 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center text-green-700 mb-6 group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fas fa-calendar-check text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Agenda & Absensi Saya</h3>
                <p class="text-gray-500 mb-6">
                    Buat agenda rapat atau kegiatan baru, kelola peserta, dan pantau kehadiran melalui QR Code secara mandiri.
                </p>
                <span class="inline-block bg-green-600 text-white px-6 py-2 rounded-full font-semibold group-hover:bg-green-700 transition">
                    Kelola Agenda <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </div>
        </a>
        @else
        {{-- TAMPILAN JIKA BELUM PUNYA AKSES --}}
        <div class="w-full max-w-lg block bg-gray-100 border border-gray-200 rounded-xl p-8 flex flex-col items-center text-center opacity-70 cursor-not-allowed">
            <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 mb-6">
                <i class="fas fa-lock text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-500 mb-2">Akses Terbatas</h3>
            <p class="text-gray-500 text-sm">
                Fitur pembuatan agenda belum diaktifkan untuk akun Anda. Hubungi Administrator untuk mendapatkan akses.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection