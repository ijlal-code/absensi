@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Buat Agenda Baru</h2>

        <form action="{{ route('event.store') }}" method="POST">
            @csrf
            
            {{-- Judul Agenda --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Agenda</label>
                <input type="text" name="title" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Rapat Evaluasi Bulanan" required>
            </div>

            {{-- Tanggal --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                <input type="date" name="date" id="date" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Jam Mulai & Selesai (Otomatis Terisi JS) --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Jam Mulai (WITA)</label>
                    <input type="time" name="start_time" id="start_time" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">*Otomatis jam sekarang</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Jam Selesai (WITA)</label>
                    <input type="time" name="end_time" id="end_time" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Lokasi / Ruangan</label>
                <input type="text" name="location" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Ruang Rapat Lt. 2" required>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-2">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-lg">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT OTOMATIS JAM LAPTOP (REALTIME) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ambil Waktu Sekarang di Laptop User
        const now = new Date();

        // 2. Format Jam & Menit menjadi 2 digit (misal: 08:05)
        const currentHours = String(now.getHours()).padStart(2, '0');
        const currentMinutes = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHours}:${currentMinutes}`;

        // 3. Set Jam Mulai = Jam Sekarang
        const startTimeInput = document.getElementById('start_time');
        startTimeInput.value = currentTime;

        // 4. Set Jam Selesai = Jam Sekarang + 2 Jam (Estimasi)
        const endTimeInput = document.getElementById('end_time');
        let endHours = now.getHours() + 2; 
        if (endHours > 23) endHours = 23; // Mentok di jam 23
        const endHoursStr = String(endHours).padStart(2, '0');
        endTimeInput.value = `${endHoursStr}:${currentMinutes}`;

        // 5. Set Tanggal Hari Ini
        const dateInput = document.getElementById('date');
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
    });
</script>
@endsection