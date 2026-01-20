<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penyelenggara - E-Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-900">Daftar Akun Baru</h1>
            <p class="text-gray-500 text-sm">Khusus untuk Penyelenggara Agenda</p>
        </div>

        @if($errors->any()) 
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penyelenggara / Organisasi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Contoh:  Nama penyelenggara" required value="{{ old('name') }}">
                </div>
                <p class="text-xs text-gray-400 mt-1">*Gunakan nama unik tanpa spasi jika memungkinkan (opsional).</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition duration-200">
                <i class="fas fa-user-plus mr-1"></i> Daftar Sekarang
            </button>
        </form>
        
        <div class="mt-6 text-center pt-4 border-t">
            <p class="text-sm text-gray-600">Sudah punya akun?</p>
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk disini</a>
        </div>
    </div>
</body>
</html>