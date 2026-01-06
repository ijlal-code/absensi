@extends('layouts.admin')
@section('title', 'Laporan & Arsip Absensi')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h3 class="text-gray-700 font-bold">Riwayat Semua Kegiatan</h3>
    </div>
    <div class="p-0">
        @foreach($events as $event)
        <div class="border-b p-4 hover:bg-gray-50 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h4 class="font-bold text-lg text-gray-800">{{ $event->title }}</h4>
                <div class="text-sm text-gray-500 mt-1">
                    <span class="mr-3"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                    <span class="mr-3"><i class="fas fa-users"></i> {{ $event->attendances_count }} Peserta</span>
                    <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm border border-blue-600 px-3 py-1 rounded">
                    Lihat Data
                </a>
                <a href="{{ route('attendance.pdf', $event->id) }}" class="bg-gray-800 text-white hover:bg-gray-900 font-medium text-sm px-4 py-1 rounded">
                    <i class="fas fa-download mr-1"></i> PDF
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection