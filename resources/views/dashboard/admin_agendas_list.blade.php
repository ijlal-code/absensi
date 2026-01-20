@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="mr-4 text-gray-500 hover:text-gray-800">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="text-2xl font-bold text-blue-900">Daftar Agenda Resmi (Admin)</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
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
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-bold">
                            {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}
                        </p>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <p class="text-gray-900 font-semibold">{{ $event->title }}</p>
                        <p class="text-gray-500 text-xs">{{ Str::limit($event->description, 50) }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        {{ $event->location }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        {{ $event->start_time }} - {{ $event->end_time }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('attendance.form', $event->id) }}" class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold hover:bg-blue-200">
                            Isi Absensi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                        Belum ada agenda resmi dari Admin saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection