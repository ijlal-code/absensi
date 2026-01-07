@extends('layouts.admin') {{-- Pastikan pakai layout admin --}}
@section('title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500 flex items-center">
        <div class="p-3 bg-blue-100 rounded-full text-blue-600 mr-4">
            <i class="fas fa-calendar-day fa-2x"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm">Acara Hari Ini</p>
            <h3 class="text-2xl font-bold">{{ $todayEvents->count() }}</h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500 flex items-center">
        <div class="p-3 bg-green-100 rounded-full text-green-600 mr-4">
            <i class="fas fa-folder-open fa-2x"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm">Total Semua Acara</p>
            <h3 class="text-2xl font-bold">{{ $totalEvents }}</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Monitoring Acara Hari Ini</h3>
        <a href="{{ route('event.create') }}" class="text-blue-600 text-sm hover:underline font-semibold">+ Buat Acara</a>
    </div>

    @if($todayEvents->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Belum ada acara hari ini.
        </div>
    @else
        <div class="divide-y divide-gray-200">
            @foreach($todayEvents as $event)
            <div class="p-5 flex flex-col md:flex-row justify-between items-start md:items-center hover:bg-gray-50 transition">
                
                <div class="mb-4 md:mb-0">
                    <h4 class="font-bold text-lg text-blue-900">{{ $event->title }}</h4>
                    <div class="text-sm text-gray-600 mt-1">
                        <i class="far fa-clock"></i> {{ $event->start_time }} - {{ $event->end_time }} | 
                        <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('event.show', $event->id) }}" class="bg-teal-100 text-teal-700 px-3 py-1 rounded text-sm hover:bg-teal-200 border border-teal-200">
                        <i class="fas fa-desktop"></i> Monitor
                    </a>

                    {{-- Tombol QR Code --}}
<a href="{{ route('event.qrcode', $event->id) }}" class="bg-purple-100 text-purple-700 px-3 py-1 rounded text-sm hover:bg-purple-200 border border-purple-200" title="QR Code">
    <i class="fas fa-qrcode"></i> QR
</a>

                    <button onclick="copyLink('{{ route('attendance.form', $event->id) }}')" class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-200 border border-gray-300">
                        <i class="fas fa-link"></i> Link
                    </button>

                    <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-sm hover:bg-yellow-200 border border-yellow-200">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus acara ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200 border border-red-200">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>
@endsection