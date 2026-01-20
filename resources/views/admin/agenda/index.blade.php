@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- Header & Tombol Kembali --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Agenda</h2>
            <p class="text-gray-500 text-sm">Atur jadwal dan monitor kehadiran hari ini.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Layout Stack Vertikal (Atas Bawah) --}}
    <div class="flex flex-col gap-8">
        
        {{-- BAGIAN ATAS: TOMBOL BUAT AGENDA --}}
        <div class="w-full">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                
                {{-- Icon & Teks --}}
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shrink-0">
                        <i class="fas fa-plus fa-2x text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">Buat Agenda Baru</h3>
                        <p class="text-blue-100 text-sm max-w-lg">
                            Jadwalkan rapat atau pertemuan baru untuk hari ini. Sistem akan otomatis membuat Link Absensi & QR Code.
                        </p>
                    </div>
                </div>

                {{-- Tombol Action --}}
                <div class="shrink-0 w-full md:w-auto">
                    <a href="{{ route('event.create') }}" class="inline-block w-full md:w-auto px-8 py-3 bg-white text-blue-700 font-bold rounded-lg shadow-lg hover:bg-blue-50 transition transform hover:-translate-y-1 text-center">
                        <i class="fas fa-calendar-plus mr-2"></i> Buat Agenda Sekarang
                    </a>
                </div>
            </div>

            
        </div>

        {{-- BAGIAN BAWAH: LIST JADWAL HARI INI --}}
        <div class="w-full">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 flex items-center gap-2">
                        <i class="far fa-clock text-green-500"></i> Jadwal Agenda Hari Ini
                    </h3>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">
                        {{ $todayEvents->count() }} Agenda Aktif
                    </span>
                </div>

                @if($todayEvents->isEmpty())
                    <div class="p-12 text-center text-gray-500 flex flex-col items-center justify-center bg-gray-50/50">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400">
                            <i class="far fa-calendar-check fa-2x"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-600">Jadwal Kosong</h4>
                        <p class="text-sm">Belum ada agenda yang dijadwalkan untuk hari ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($todayEvents as $event)
                            @php
                                // Cek apakah pembuatnya admin
                                $isAdminEvent = $event->user->role === 'admin';
                                
                                // Cek apakah ini agenda admin yang paling baru dibuat
                                $isLatestAdmin  = ($event->id === $latestAdminEventId);
                            @endphp

                        <div class="p-5 transition group border-l-4 {{ $isAdminEvent ? 'bg-blue-50/50 border-blue-600' : 'bg-white border-gray-300' }} hover:shadow-md">
                            <div class="flex flex-col md:flex-row justify-between gap-4 items-start md:items-center">
                                
                                {{-- Info Agenda --}}
                                <div class="flex-grow">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        
                                        {{-- LOGIK BADGE TERBARU (Hanya untuk Admin Event Terakhir) --}}
                                        @if($isLatestAdmin)
                                            <span class="px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wide rounded shadow-sm animate-pulse">
                                                Terbaru
                                            </span>
                                        @endif

                                        {{-- BADGE TIPE AGENDA --}}
                                        @if($isAdminEvent)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold uppercase tracking-wide rounded border border-blue-200">
                                                <i class="fas fa-building mr-1"></i> Resmi
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wide rounded border border-gray-200">
                                                <i class="fas fa-user mr-1"></i> Personal
                                            </span>
                                        @endif

                                        <h4 class="font-bold text-lg text-gray-800 group-hover:text-blue-700 transition">
                                            {{ $event->title }}
                                        </h4>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
                                        <div class="flex items-center gap-1.5">
                                            <i class="far fa-clock text-orange-500"></i> 
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt text-red-500"></i> 
                                            {{ $event->location }}
                                        </div>
                                        
                                        {{-- JIKA AGENDA USER: Tampilkan Nama Pembuatnya --}}
                                        @if(!$isAdminEvent)
                                        <div class="flex items-center gap-1.5 text-gray-400">
                                            <i class="fas fa-user-circle"></i> 
                                            Oleh: <span class="font-semibold text-gray-600">{{ $event->user->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tombol Aksi (Sama seperti sebelumnya) --}}
                                <div class="flex flex-wrap items-center gap-2 shrink-0 opacity-80 group-hover:opacity-100 transition">
                                    <a href="{{ route('event.monitor', $event->id) }}" class="inline-flex items-center gap-1 bg-teal-50 text-teal-700 px-3 py-2 rounded-lg text-sm hover:bg-teal-100 border border-teal-200 transition font-medium" title="Monitor Layar">
                                        <i class="fas fa-desktop"></i> <span class="hidden sm:inline">Monitor</span>
                                    </a>

                                    <a href="{{ route('event.qrcode', $event->id) }}" class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-3 py-2 rounded-lg text-sm hover:bg-indigo-100 border border-indigo-200 transition font-medium" title="Tampilkan QR Code">
                                        <i class="fas fa-qrcode"></i> <span class="hidden sm:inline">QR Code</span>
                                    </a>

                                    <div class="h-6 w-px bg-gray-300 mx-1 hidden md:block"></div>

                                    <button onclick="copyLink('{{ route('attendance.form', $event->id) }}')" class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-200 border border-gray-200 transition" title="Salin Link">
                                        <i class="fas fa-link"></i>
                                    </button>

                                    <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-50 text-yellow-600 px-3 py-2 rounded-lg text-sm hover:bg-yellow-100 border border-yellow-200 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-red-50 text-red-600 rounded-lg border border-red-200 hover:bg-red-600 hover:text-white transition" title="Hapus Agenda">
                                            <i class="fas fa-trash-alt"></i>
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

{{-- SCRIPT: Copy Link & Toast --}}
<script>
    function copyLink(url) {
        // Cek dukungan clipboard API modern
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(showToast);
        } else {
            // Fallback untuk browser lama atau non-HTTPS
            let textArea = document.createElement("textarea");
            textArea.value = url;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showToast(); // Panggil notifikasi sukses
            } catch (err) {
                console.error('Gagal menyalin link', err);
            }
            document.body.removeChild(textArea);
        }
    }

    // Fungsi menampilkan Toast SweetAlert2 (Profesional & Minimalis)
    function showToast() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#fff',
            color: '#1f2937', // Abu-abu gelap
            iconColor: '#10b981', // Hijau (Sukses)
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'rounded-xl shadow-xl border border-gray-100'
            }
        });

        Toast.fire({
            icon: 'success',
            title: 'Link berhasil disalin!'
        });
    }
</script>
@endsection