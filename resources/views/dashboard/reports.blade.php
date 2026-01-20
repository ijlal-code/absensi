@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan & Rekapitulasi</h2>
            <p class="text-gray-500 mt-1">Riwayat lengkap seluruh agenda dan data absensi.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition font-medium flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
             <h3 class="font-bold text-gray-700 flex items-center gap-2">
                <i class="fas fa-history text-blue-500"></i> Arsip Agenda
             </h3>
             <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                {{ $events->count() }} Data Tersimpan
             </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Waktu Pelaksanaan</th>
                        <th class="px-6 py-4">Nama Agenda</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4 text-center">Total Hadir</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $index => $event)
                    <tr class="hover:bg-blue-50/50 transition duration-150">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                        
                        {{-- Kolom Waktu --}}
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-700">
                                {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="far fa-clock"></i>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WITA
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 font-semibold text-blue-900">
                            {{ $event->title }}
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-1 text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-400"></i> {{ $event->location }}
                            </span>
                        </td>
                        
                        {{-- Kolom Statistik --}}
                        <td class="px-6 py-4 text-center">
                            @if($event->attendances_count > 0)
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $event->attendances_count }} Peserta
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded-full">
                                    0 Peserta
                                </span>
                            @endif
                        </td>
                        
                        {{-- Kolom Tombol Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Detail / Monitor (Diarahkan ke halaman show) --}}
                                <a href="{{ route('event.monitor', $event->id) }}" class="group relative px-3 py-2 bg-blue-50 text-blue-600 rounded-lg border border-blue-200 hover:bg-blue-600 hover:text-white transition" title="Lihat Detail & Daftar Hadir">
                                    <i class="fas fa-desktop"></i>
                                    <span class="sr-only">Detail</span>
                                </a>

                                {{-- Tombol Download PDF --}}
                                <a href="{{ route('attendance.pdf', $event->id) }}" class="group relative px-3 py-2 bg-red-50 text-red-600 rounded-lg border border-red-200 hover:bg-red-600 hover:text-white transition" title="Download Laporan PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                    <span class="sr-only">PDF</span>
                                </a>

                                {{-- TAMBAHAN: Tombol Hapus --}}
                                <form action="{{ route('event.destroy', $event->id) }}" method="POST" class="delete-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="group relative px-3 py-2 bg-red-50 text-red-600 rounded-lg border border-red-200 hover:bg-red-600 hover:text-white transition" title="Hapus Agenda">
        <i class="fas fa-trash-alt"></i>
        <span class="sr-only">Hapus</span>
    </button>
</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada riwayat agenda yang tersimpan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination (Jika diperlukan di masa depan) --}}
        {{-- <div class="px-6 py-4 border-t bg-gray-50">
            {{ $events->links() }}
        </div> --}}
    </div>
</div>
@endsection