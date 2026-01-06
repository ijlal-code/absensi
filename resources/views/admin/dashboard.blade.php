@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-calendar-day fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Kegiatan Hari Ini</p>
                <h3 class="text-2xl font-bold">{{ $todayEvents->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <i class="fas fa-history fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Kegiatan</p>
                <h3 class="text-2xl font-bold">{{ $totalEvents }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="font-bold text-gray-800">Absensi Hari Ini</h3>
    </div>
    <div class="p-6">
        @if($todayEvents->isEmpty())
            <p class="text-gray-500 text-center py-4">Belum ada jadwal hari ini. <a href="{{ route('admin.create') }}" class="text-blue-600 underline">Buat sekarang</a></p>
        @else
            <div class="grid gap-4">
                @foreach($todayEvents as $event)
                <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 transition">
                    <div>
                        <h4 class="font-bold text-lg">{{ $event->title }}</h4>
                        <p class="text-sm text-gray-600">
                            <i class="far fa-clock"></i> {{ $event->start_time }} - {{ $event->end_time }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                        </p>
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ $event->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_open ? 'Sedang Berlangsung' : 'Tutup' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.show', $event->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye"></i> Monitor
                        </a>
                        <button onclick="copyLink('{{ $event->link }}')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                            <i class="fas fa-link"></i> Salin Link
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url);
    alert('Link absensi berhasil disalin!');
}
</script>
@endsection