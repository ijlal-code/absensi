@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-xl font-bold mb-4 border-b pb-2">Buat Jadwal Absensi</h2>
    
    <button type="button" onclick="setRealtime()" class="mb-4 bg-indigo-100 text-indigo-700 px-4 py-2 rounded text-sm hover:bg-indigo-200 w-full md:w-auto">
        <i class="fas fa-magic mr-1"></i> Isi Tanggal & Jam Sekarang (Realtime)
    </button>

    <form action="{{ route('event.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-1">Nama Acara</label>
            <input type="text" name="title" class="w-full border p-2 rounded focus:ring focus:ring-blue-200" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Tanggal</label>
                <input type="date" id="date" name="date" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Jam Mulai</label>
                <input type="time" id="start_time" name="start_time" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Jam Selesai</label>
                <input type="time" id="end_time" name="end_time" class="w-full border p-2 rounded" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-1">Lokasi</label>
            <input type="text" name="location" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">Simpan Acara</button>
    </form>
</div>

<script>
    function setRealtime() {
        const now = new Date();
        
        // Format Tanggal YYYY-MM-DD
        const dateStr = now.toISOString().split('T')[0];
        document.getElementById('date').value = dateStr;

        // Format Jam HH:MM
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('start_time').value = `${hours}:${minutes}`;

        // Set Jam Selesai otomatis +2 jam
        const endHours = String((now.getHours() + 2) % 24).padStart(2, '0');
        document.getElementById('end_time').value = `${endHours}:${minutes}`;
    }
</script>
@endsection