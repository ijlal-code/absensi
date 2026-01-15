@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    
    {{-- BAGIAN 1: MENU PILIHAN UTAMA --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Menu Utama</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- KARTU 1: MANAJEMEN ACARA (Absensi) --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-600 hover:shadow-lg transition duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-3 bg-blue-100 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Manajemen Acara</h3>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm h-10">
                            Buat jadwal acara baru, pantau kehadiran peserta, dan kelola QR Code absensi.
                        </p>
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <a href="{{ route('event.create') }}" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-plus mr-1"></i> Buat Acara
                    </a>
                </div>
            </div>

            {{-- KARTU 2: KELOLA KARYAWAN --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-600 hover:shadow-lg transition duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-3 bg-teal-100 rounded-lg text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Data Karyawan</h3>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm h-10">
                            Lihat informasi karyawan, tambah data baru, atau perbarui data karyawan Semen Tonasa.
                        </p>
                    </div>
                </div>
                
                {{-- GROUP TOMBOL KARYAWAN --}}
                <div class="flex flex-col gap-2 mt-2">
                    <div class="flex gap-2">
                        {{-- Tombol Kelola (CRUD) --}}
                        <a href="{{ route('employee-management.index') }}" class="flex-1 text-center bg-teal-600 text-white px-3 py-2 rounded-lg hover:bg-teal-700 transition font-medium text-sm">
                            <i class="fas fa-user-cog mr-1"></i> Kelola
                        </a>
                        {{-- Tombol Info (Pencarian) --}}
                        <a href="{{ route('employees.index') }}" class="flex-1 text-center bg-teal-100 text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-200 transition font-medium text-sm">
                            <i class="fas fa-search mr-1"></i> Info
                        </a>
                    </div>
                    {{-- Tombol Statistik (BARU) --}}
                   <a href="{{ route('employee.stats') }}" class="w-full text-center border border-teal-600 text-teal-600 px-3 py-2 rounded-lg hover:bg-teal-50 transition font-medium text-sm">
    <i class="fas fa-chart-pie mr-1"></i> Statistik & Persentase
</a>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: DAFTAR ACARA HARI INI (Monitoring) --}}
    <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 flex items-center">
                <i class="fas fa-clock text-gray-400 mr-2"></i> Jadwal Acara Hari Ini
            </h3>
            {{-- Badge jumlah event --}}
            @if(!$todayEvents->isEmpty())
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {{ $todayEvents->count() }} Acara
                </span>
            @endif
        </div>
        
        @if($todayEvents->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                    <i class="fas fa-calendar-check text-4xl text-gray-300"></i>
                </div>
                <p>Tidak ada jadwal acara aktif hari ini.</p>
                <p class="text-sm mt-2">Silakan gunakan menu <b>"Manajemen Acara"</b> di atas untuk membuat jadwal.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($todayEvents as $event)
                <div class="p-5 flex flex-col md:flex-row justify-between items-start md:items-center hover:bg-gray-50 transition">
                    
                    <div class="mb-4 md:mb-0">
                        <h4 class="font-bold text-lg text-blue-900">{{ $event->title }}</h4>
                        <div class="text-sm text-gray-600 mt-1 space-y-1">
                            <p><i class="far fa-clock mr-2 w-4 text-center"></i> {{ $event->start_time }} - {{ $event->end_time }}</p>
                            <p><i class="fas fa-map-marker-alt mr-2 w-4 text-center"></i> {{ $event->location }}</p>
                        </div>
                        <div class="mt-2">
                             <span class="px-2 py-1 text-xs rounded-full {{ $event->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_open ? 'Sedang Berlangsung' : 'Tutup' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('event.show', $event->id) }}" class="bg-teal-100 text-teal-700 px-3 py-2 rounded text-sm hover:bg-teal-200 border border-teal-200 transition" title="Monitor Kehadiran">
                            <i class="fas fa-desktop mr-1"></i> Monitor
                        </a>

                        <a href="{{ route('event.qrcode', $event->id) }}" class="bg-purple-100 text-purple-700 px-3 py-2 rounded text-sm hover:bg-purple-200 border border-purple-200 transition" title="Tampilkan QR Code">
                            <i class="fas fa-qrcode mr-1"></i> QR
                        </a>

                        <button onclick="copyLink('{{ route('attendance.form', $event->id) }}')" class="bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-200 border border-gray-300 transition" title="Salin Link Absensi">
                            <i class="fas fa-link mr-1"></i> Link
                        </button>

                        <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded text-sm hover:bg-yellow-200 border border-yellow-200 transition" title="Edit Jadwal">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini? Data absensi akan ikut terhapus.');" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded text-sm hover:bg-red-200 border border-red-200 transition" title="Hapus Acara">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>
        @endif
    </div>
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