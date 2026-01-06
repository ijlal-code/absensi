<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-blue-900 mb-6">Login Sistem Absensi</h1>
        
        @if(session('success')) <p class="text-green-500 text-sm mb-4 text-center">{{ session('success') }}</p> @endif
        @if($errors->any()) <p class="text-red-500 text-sm mb-4 text-center">{{ $errors->first() }}</p> @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Akun</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-300" required>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 rounded hover:bg-blue-800 transition">Masuk</button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Belum punya akun? Daftar Penyelenggara</a>
        </div>
    </div>
</body>
</html>