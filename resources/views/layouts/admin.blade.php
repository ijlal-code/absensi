<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div class="bg-blue-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="text-2xl font-bold text-center mb-10">
                <i class="fas fa-calendar-check mr-2"></i>E-Absensi
            </div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.create') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.create') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Absensi
                </a>
                <a href="{{ route('admin.reports') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.reports') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i> Laporan & Download
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    @yield('title', 'Admin Dashboard')
                </h2>
                </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>