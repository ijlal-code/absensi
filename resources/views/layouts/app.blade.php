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
                
                {{-- Menu Dashboard User Biasa --}}
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-home mr-2 w-6"></i> Dashboard
                    </a>
                @endif
            
                {{-- MENU 1: INFORMASI KARYAWAN (View Only) --}}
                <a href="{{ route('employees.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employees.index') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-info-circle mr-2 w-6"></i> Informasi Karyawan
                </a>

                {{-- BARU: MENU STATISTIK KARYAWAN --}}
                <a href="{{ route('employee-management.stats') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employee.stats') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-chart-pie mr-2 w-6"></i> Statistik Karyawan
                </a>
            
                {{-- MENU 2: KELOLA KARYAWAN (CRUD) --}}
                <a href="{{ route('employee-management.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('employee-management.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-user-edit mr-2 w-6"></i> Kelola Karyawan
                </a>
            
                {{-- Menu Lainnya --}}
                <a href="{{ route('reports') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('reports') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-export mr-2 w-6"></i> Laporan
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
</body>
</html>