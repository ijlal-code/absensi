@extends('layouts.admin')
@section('title', 'Buat Jadwal Absensi Baru')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl">
    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Acara / Kegiatan</label>
            <input type="text" name="title" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Rapat Evaluasi Bulanan">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full border p-2 rounded" required value="{{ date('Y-m-d') }}">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                <input type="time" name="start_time" class="w-full border p-2 rounded" required value="{{ date('H:i') }}">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Jam Selesai (Batas)</label>
                <input type="time" name="end_time" class="w-full border p-2 rounded" required>
                <p class="text-xs text-red-500 mt-1">*Link tidak akan bisa diakses setelah jam ini.</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Tempat / Lokasi</label>
            <input type="text" name="location" class="w-full border p-2 rounded" required placeholder="Contoh: Ruang Meeting Lt. 2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 w-full md:w-auto">
            <i class="fas fa-save mr-2"></i> Buat & Dapatkan Link
        </button>
    </form>
</div>
@endsection