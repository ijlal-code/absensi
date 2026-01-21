<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

    {{-- Header Mobile --}}
    <div class="md:hidden bg-blue-900 text-white p-4 flex justify-between items-center">
        <span class="font-bold text-xl">E-Absensi</span>
        <button @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-blue-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out z-20">
            <div class="text-2xl font-bold text-center mb-10 hidden md:block">
                <i class="fas fa-calendar-check mr-2"></i>E-Absensi
            </div>
            
            <div class="px-4 mb-4 text-sm text-gray-400">
                User: <span class="text-white font-bold">{{ Auth::user()->name }}</span>
            </div>

            <nav class="mt-4">
                
    {{-- Dashboard: Semua bisa akses, tapi isinya beda di Controller --}}
    <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-home mr-2 w-6"></i> Dashboard
    </a>

    

    {{-- MENU 1: INFORMASI KARYAWAN --}}
    {{-- Logic: Admin ATAU User yang punya permission 'view_employees' --}}
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_employees'))
    <a href="{{ route('employees.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employees.index') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-info-circle mr-2 w-6"></i> Informasi Karyawan
    </a>
    @endif

    {{-- MENU STATISTIK --}}
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_statistics'))
    {{-- PERBAIKAN: Ubah 'employee.stats' menjadi 'employee-management.stats' --}}
    <a href="{{ route('employee-management.stats') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employee-management.stats') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-chart-pie mr-2 w-6"></i> Statistik Karyawan
    </a>
    @endif

    {{-- MENU KELOLA KARYAWAN (CRUD) --}}
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_employees'))
    {{-- PERBAIKAN: Tambahkan pengecekan && !request()->routeIs('employee-management.stats') agar tidak bentrok --}}
    <a href="{{ route('employee-management.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employee-management.*') && !request()->routeIs('employee-management.stats') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-user-edit mr-2 w-6"></i> Kelola Karyawan
    </a>
    @endif

    {{-- MENU LAPORAN --}}
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_reports'))
    <a href="{{ route('reports') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('reports') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-file-export mr-2 w-6"></i> Laporan
    </a>
    @endif

    {{-- MENU MANAGEMENT USER (HANYA ADMIN) --}}
    @if(Auth::user()->isAdmin())
    <div class="mt-4 pt-4 border-t border-blue-800">
        <p class="px-4 text-xs text-gray-400 uppercase mb-2">Administrator</p>
        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-users-cog mr-2 w-6"></i> Management User
        </a>
    </div>
    @endif

    {{-- SEPARATOR --}}
    <div class="px-4 mt-6 mb-2 text-xs text-gray-400 uppercase">Agenda Rapat</div>

    {{-- MENU RAPAT --}}
    <a href="{{ route('meetings.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('meetings.index') || request()->routeIs('meetings.create') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-handshake mr-2 w-6"></i> Agenda Rapat
    </a>

    <a href="{{ route('meetings.reports') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('meetings.reports') ? 'bg-blue-700' : '' }}">
        <i class="fas fa-file-contract mr-2 w-6"></i> Laporan Rapat
    </a>

</nav>

            <div class="absolute bottom-0 left-0 w-full p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-white text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay untuk mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-10 md:hidden" style="display: none;"></div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    {{-- 1. CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. Script Global Konfirmasi Hapus --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap semua form dengan class "delete-form"
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Cegah submit langsung

                    const formId = this; // Simpan referensi form

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444', // Warna Merah (Tailwind red-500)
                        cancelButtonColor: '#3b82f6',  // Warna Biru (Tailwind blue-500)
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true, // Tombol batal di kiri, hapus di kanan (opsional)
                        focusCancel: true // Default fokus ke tombol batal (safety)
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formId.submit(); // Submit form jika user klik Ya
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>