@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-xl font-bold text-gray-800">Edit Agenda</h2>
        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-blue-600 text-sm">Kembali</a>
    </div>

    <form action="{{ route('event.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- PENTING: Method PUT untuk update --}}

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Agenda</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                <input type="date" name="date" value="{{ old('date', $event->date) }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time', $event->start_time) }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Jam Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time', $event->end_time) }}" class="w-full border p-2 rounded" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Lokasi</label>
            <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="flex space-x-3">
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">Simpan Perubahan</button>
            <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection