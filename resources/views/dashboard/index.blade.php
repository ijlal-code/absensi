@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Penyelenggara</h2>
    <a href="{{ route('event.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 text-sm">
        <i class="fas fa-plus"></i> Buat Acara Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h3 class="font-bold text-gray-700">Acara Hari Ini</h3>
    </div>
    
    @if($todayEvents->isEmpty())
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300"></i>
            <p>Tidak ada jadwal acara hari ini.</p>
        </div>
    @else
        <div class="divide-y divide-gray-200">
            @foreach($todayEvents as $event)
            <div class="p-5 flex flex-col md:flex-row justify-between items-start md:items-center hover:bg-gray-50 transition">
                
                <div class="mb-4 md:mb-0">
                    <h4 class="font-bold text-lg text-blue-900">{{ $event->title }}</h4>
                    <div class="text-sm text-gray-600 mt-1 space-y-1">
                        <p><i class="far fa-clock mr-2 w-4"></i> {{ $event->start_time }} - {{ $event->end_time }}</p>
                        <p><i class="fas fa-map-marker-alt mr-2 w-4"></i> {{ $event->location }}</p>
                    </div>
                    <div class="mt-2">
                         <span class="px-2 py-1 text-xs rounded-full {{ $event->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $event->is_open ? 'Sedang Berlangsung' : 'Tutup' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('event.show', $event->id) }}" class="bg-teal-100 text-teal-700 px-3 py-2 rounded text-sm hover:bg-teal-200 border border-teal-200 transition" title="Monitor">
                        <i class="fas fa-desktop mr-1"></i> Monitor
                    </a>

                    {{-- Tombol QR Code --}}
<a href="{{ route('event.qrcode', $event->id) }}" class="bg-purple-100 text-purple-700 px-3 py-2 rounded text-sm hover:bg-purple-200 border border-purple-200 transition" title="Lihat QR Code">
    <i class="fas fa-qrcode mr-1"></i> QR Code
</a>

                    <button onclick="copyLink('{{ route('attendance.form', $event->id) }}')" class="bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-200 border border-gray-300 transition" title="Salin Link">
                        <i class="fas fa-link mr-1"></i> Salin Link
                    </button>

                    <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded text-sm hover:bg-yellow-200 border border-yellow-200 transition" title="Edit">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>

                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini? Data absensi akan ikut terhapus.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded text-sm hover:bg-red-200 border border-red-200 transition" title="Hapus">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus
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
        alert('Link absensi berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endsection