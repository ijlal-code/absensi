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

    <div class="md:hidden bg-blue-900 text-white p-4 flex justify-between items-center">
        <span class="font-bold text-xl">E-Absensi</span>
        <button @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <div class="flex h-screen overflow-hidden">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-blue-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out z-20">
            <div class="text-2xl font-bold text-center mb-10 hidden md:block">
                <i class="fas fa-calendar-check mr-2"></i>E-Absensi
            </div>
            
            <div class="px-4 mb-4 text-sm text-gray-400">
                User: <span class="text-white font-bold">{{ Auth::user()->name }}</span>
            </div>

            <nav class="mt-4">
                @if(Auth::user()->isAdmin())
                    <p class="px-4 text-xs text-blue-300 uppercase font-bold mb-2">Administrator</p>
                    <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-line mr-2 w-6"></i> Dashboard Admin
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-users-cog mr-2 w-6"></i> Kelola User
                    </a>
                @else
                    <p class="px-4 text-xs text-gray-400 uppercase font-bold mb-2">Penyelenggara</p>
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-home mr-2 w-6"></i> Dashboard Saya
                    </a>
                    <a href="{{ route('event.create') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('event.create') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-plus-circle mr-2 w-6"></i> Buat Acara Baru
                    </a>
                @endif

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

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-10 md:hidden"></div>

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