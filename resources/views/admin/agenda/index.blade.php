@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- Header & Tombol Kembali --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Agenda</h2>
            <p class="text-gray-500 text-sm">Atur jadwal dan monitor kehadiran hari ini.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- PANEL KIRI: TOMBOL BUAT AGENDA --}}
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-plus fa-2x text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Buat Agenda Baru</h3>
                <p class="text-blue-100 text-sm mb-6">Jadwalkan rapat baru untuk mendapatkan Link Absensi & QR Code.</p>
                
                <a href="{{ route('event.create') }}" class="block w-full py-3 bg-white text-blue-700 font-bold rounded-lg shadow hover:bg-blue-50 transition transform hover:-translate-y-1">
                    + Buat Agenda Sekarang
                </a>
            </div>

            <div class="mt-6 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-gray-700 mb-2 text-sm"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Informasi</h4>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Agenda yang dibuat akan otomatis muncul di daftar "Jadwal Hari Ini" sesuai tanggal pelaksanaannya.
                </p>
            </div>
        </div>

        {{-- PANEL KANAN: LIST JADWAL HARI INI --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 flex items-center gap-2">
                        <i class="far fa-clock text-green-500"></i> Jadwal Agenda Hari Ini
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $todayEvents->count() }} Agenda
                    </span>
                </div>

                @if($todayEvents->isEmpty())
                    <div class="p-10 text-center text-gray-500 flex flex-col items-center">
                        <i class="far fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                        <p>Tidak ada agenda terjadwal untuk hari ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($todayEvents as $event)
                        <div class="p-5 hover:bg-gray-50 transition group">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg text-blue-900">{{ $event->title }}</h4>
                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-clock text-orange-400 w-5"></i> 
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-red-400 w-5"></i> 
                                            {{ $event->location }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 shrink-0 self-start md:self-center">
                                    {{-- Tombol Aksi --}}
                                    <a href="{{ route('event.show', $event->id) }}" class="bg-teal-50 text-teal-600 px-3 py-2 rounded text-sm hover:bg-teal-100 border border-teal-100 transition" title="Monitor">
                                        <i class="fas fa-desktop"></i>
                                    </a>
                                    <a href="{{ route('event.qrcode', $event->id) }}" class="bg-purple-50 text-purple-600 px-3 py-2 rounded text-sm hover:bg-purple-100 border border-purple-100 transition" title="QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <button onclick="copyLink('{{ route('attendance.form', $event->id) }}')" class="bg-gray-100 text-gray-600 px-3 py-2 rounded text-sm hover:bg-gray-200 border border-gray-200 transition" title="Copy Link">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-50 text-yellow-600 px-3 py-2 rounded text-sm hover:bg-yellow-100 border border-yellow-100 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus Agenda ini?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 px-3 py-2 rounded text-sm hover:bg-red-100 border border-red-100 transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>
@endsection