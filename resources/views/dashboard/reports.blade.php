@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Laporan & Arsip</h2>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                <th class="py-3 px-6 text-left">Tanggal</th>
                <th class="py-3 px-6 text-left">Acara</th>
                <th class="py-3 px-6 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
            @foreach($events as $event)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $event->date }}</td>
                <td class="py-3 px-6 text-left">{{ $event->title }}</td>
                <td class="py-3 px-6 text-center flex justify-center space-x-2">
                    <a href="{{ route('attendance.pdf', $event->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                        <i class="fas fa-download"></i> PDF
                    </a>
                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus arsip ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection