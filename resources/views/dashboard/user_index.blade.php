@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Agenda Resmi (Dari Admin)</h1>
        {{-- Tombol Buat Agenda Sendiri --}}
        <a href="{{ route('event.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Buat Agenda Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($adminEvents as $event)
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-gray-500 text-sm mb-1">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}</div>
                <h3 class="text-xl font-bold mb-2 text-blue-900">{{ $event->title }}</h3>
                <p class="text-gray-600 mb-4"><i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}</p>
                <p class="text-gray-600 mb-4"><i class="fas fa-clock mr-1"></i> {{ $event->start_time }} - {{ $event->end_time }}</p>
                
                <a href="{{ route('attendance.form', $event->id) }}" class="block text-center bg-blue-100 text-blue-800 py-2 rounded mt-2 hover:bg-blue-200">
                    Lihat Absensi / QR
                </a>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 py-10">
                Belum ada agenda dari Admin.
            </div>
        @endforelse
    </div>
</div>
@endsection