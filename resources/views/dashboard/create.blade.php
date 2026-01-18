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

            {{-- Jam Mulai & Selesai --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Jam Mulai (WITA)</label>
                    <input type="time" name="start_time" id="start_time" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                <a href="{{ route('event.agenda') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-lg">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        
        // Helper untuk format 2 digit
        const pad = (num) => String(num).padStart(2, '0');

        // Set Tanggal Hari Ini
        const dateInput = document.getElementById('date');
        dateInput.value = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;

        // Set Jam Sekarang
        const startTimeInput = document.getElementById('start_time');
        const currentHours = now.getHours();
        const currentMinutes = now.getMinutes();
        startTimeInput.value = `${pad(currentHours)}:${pad(currentMinutes)}`;

        // Set Jam Selesai (+2 Jam)
        const endTimeInput = document.getElementById('end_time');
        let endHours = currentHours + 2;
        if (endHours > 23) endHours = 23;
        endTimeInput.value = `${pad(endHours)}:${pad(currentMinutes)}`;
    });
</script>
@endsection