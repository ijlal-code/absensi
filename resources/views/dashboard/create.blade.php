@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Buat Absensi Baru</h2>

    <form action="{{ route('event.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Acara</label>
            <input type="text" name="title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Rapat Bulanan">
        </div>

        <div class="mb-2 flex justify-end">
            <button type="button" onclick="setToday()" class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition">
                <i class="fas fa-clock mr-1"></i> Isi Waktu Sekarang
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pelaksanaan</label>
            <input type="date" name="date" id="date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                <input type="time" name="start_time" id="start_time" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai</label>
                <input type="time" name="end_time" id="end_time" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi / Ruangan</label>
            <input type="text" name="location" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Ruang Meeting A">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            Buat Absensi
        </button>
    </form>
</div>

<script>
    function setToday() {
        const now = new Date();
        
        // Format Tanggal YYYY-MM-DD (Lokal)
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;

        // Format Waktu HH:MM
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const timeStr = `${hours}:${minutes}`;

        // Waktu Selesai (Default +2 Jam)
        const endData = new Date(now.getTime() + (2 * 60 * 60 * 1000));
        const endHours = String(endData.getHours()).padStart(2, '0');
        const endMinutes = String(endData.getMinutes()).padStart(2, '0');
        const endTimeStr = `${endHours}:${endMinutes}`;

        // Set Nilai Input
        document.getElementById('date').value = todayStr;
        document.getElementById('start_time').value = timeStr;
        document.getElementById('end_time').value = endTimeStr;
    }
</script>
@endsection