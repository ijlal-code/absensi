<!DOCTYPE html>
<html lang="id">
<head>
    <title>Absensi Ditutup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md">
        <div class="text-red-500 text-6xl mb-4">
            <i class="fas fa-times-circle"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Absensi Ditutup</h1>
        <p class="text-gray-600 mb-6">Maaf, batas waktu pengisian absensi untuk acara <strong>{{ $event->title }}</strong> sudah berakhir pada pukul {{ $event->end_time }}.</p>
    </div>
</body>
</html>