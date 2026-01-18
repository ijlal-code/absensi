@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Agenda</h2>
            <a href="{{ route('event.agenda') }}" class="text-gray-600 hover:text-blue-600 text-sm">Kembali</a>
        </div>

        <form action="{{ route('event.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Agenda</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- 
               PERBAIKAN: Memecah datetime DB menjadi Date & Time terpisah 
               Format input type="date" butuh Y-m-d
               Format input type="time" butuh H:i
            --}}
            @php
                $startDate = \Carbon\Carbon::parse($event->start_time);
                $endDate = \Carbon\Carbon::parse($event->end_time);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', $startDate->format('Y-m-d')) }}" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $startDate->format('H:i')) }}" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $endDate->format('H:i')) }}" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="flex space-x-3 justify-end">
                <a href="{{ route('event.agenda') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded hover:bg-gray-300">Batal</a>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection