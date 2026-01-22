<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        /* Custom Scrollbar untuk Sidebar agar terlihat rapi */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #1e3a8a; 
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #3b82f6; 
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

    <div class="md:hidden bg-blue-900 text-white p-4 flex justify-between items-center sticky top-0 z-20 shadow-md">
        <span class="font-bold text-xl">E-Absensi</span>
        <button @click="sidebarOpen = !sidebarOpen" class="focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="bg-blue-900 text-white w-64 flex flex-col fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out md:relative md:translate-x-0 shadow-xl">
            
            <div class="p-4 flex-shrink-0">
                <div class="text-2xl font-bold text-center mb-6 hidden md:block">
                    <i class="fas fa-calendar-check mr-2"></i>E-Absensi
                </div>
                
                <div class="bg-blue-800 rounded p-3 text-sm text-gray-200 shadow-inner">
                    <div class="text-xs text-gray-400">Login sebagai:</div>
                    <div class="text-white font-bold truncate">{{ Auth::user()->name }}</div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto sidebar-scroll pb-4">
                
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i> Dashboard
                </a>

                {{-- SEPARATOR --}}
                <div class="mt-6 mb-2 text-xs text-gray-400 uppercase font-semibold tracking-wider px-2">Agenda Rapat</div>

                <a href="{{ route('meetings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('meetings.index') || request()->routeIs('meetings.create') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-handshake mr-3 w-5 text-center"></i> Agenda Rapat
                </a>

                <a href="{{ route('meetings.reports') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('meetings.reports') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-contract mr-3 w-5 text-center"></i> Laporan Rapat
                </a>

                {{-- KARYAWAN --}}
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_employees'))
                <div class="mt-6 mb-2 text-xs text-gray-400 uppercase font-semibold tracking-wider px-2">Kepegawaian</div>
                
                <a href="{{ route('employees.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('employees.index') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-info-circle mr-3 w-5 text-center"></i> Info Karyawan
                </a>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_statistics'))
                <a href="{{ route('employee-management.stats') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('employee-management.stats') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-chart-pie mr-3 w-5 text-center"></i> Statistik
                </a>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('manage_employees'))
                <a href="{{ route('employee-management.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('employee-management.*') && !request()->routeIs('employee-management.stats') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-user-edit mr-3 w-5 text-center"></i> Kelola Karyawan
                </a>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('view_reports'))
                <a href="{{ route('reports') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('reports') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-export mr-3 w-5 text-center"></i> Laporan
                </a>
                @endif

                {{-- ADMIN --}}
                @if(Auth::user()->isAdmin())
                <div class="mt-6 mb-2 text-xs text-gray-400 uppercase font-semibold tracking-wider px-2">Administrator</div>
                
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users-cog mr-3 w-5 text-center"></i> User Management
                </a>
                @endif
                
                <div class="h-4"></div>
            </nav>

            <div class="p-4 border-t border-blue-800 flex-shrink-0 bg-blue-900">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 transition duration-200 py-2 px-4 rounded text-white text-sm flex items-center justify-center font-semibold shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-50"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-50"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black z-20 md:hidden" 
             style="display: none;">
        </div>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                        <div>{{ session('success') }}</div>
                        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- CDN SweetAlert2 & Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formId = this;
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#3b82f6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formId.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>