@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6"> {{-- Tambahkan padding --}}
    
    {{-- BAGIAN ATAS: Judul dan Tombol Kembali --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-900">
            <i class="fas fa-list-alt mr-2"></i> Daftar Agenda Resmi (Admin)
        </h1>
        
        {{-- Tombol Kembali Atas --}}
        <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Kegiatan</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Lokasi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Waktu</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adminEvents as $event)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-bold">
                            {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}
                        </p>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 font-semibold">{{ $event->title }}</p>
                        <p class="text-gray-500 text-xs mt-1">{{ Str::limit($event->description, 60) }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1 text-red-400"></i> {{ $event->location }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        <i class="far fa-clock mr-1 text-blue-400"></i> {{ $event->start_time }} - {{ $event->end_time }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        {{-- Tombol Isi Absensi (Target _blank) --}}
                        <a href="{{ route('attendance.form', $event->id) }}" target="_blank" class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-xs font-bold hover:bg-blue-200 transition">
                            Isi Absensi <i class="fas fa-external-link-alt ml-2"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="far fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada agenda resmi dari Admin saat ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    
</div>
@endsection