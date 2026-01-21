@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Laporan & Arsip Minutes of Meeting</h2>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Agenda</th>
                    <th class="p-4">Status Item</th>
                    <th class="p-4 text-center">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y') }}</td>
                    <td class="p-4">
                        <div class="font-bold">{{ $meeting->type_of_meeting }}</div>
                        <div class="text-sm text-gray-500">Facilitator: {{ $meeting->facilitator }}</div>
                    </td>
                    <td class="p-4">
                        @if($meeting->hasDueDeadlines())
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Overdue / Warning</span>
                        @else
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">On Track</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <a href="{{ route('meetings.pdf', $meeting->id) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection