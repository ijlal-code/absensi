@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Agenda Rapat (MOM)</h2>
        <a href="{{ route('meetings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
            <i class="fas fa-plus mr-2"></i> Buat Agenda Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">Topik / Tipe Rapat</th>
                        <th class="py-3 px-6 text-left">Fasilitator & Lokasi</th>
                        <th class="py-3 px-6 text-center">Jml Action Item</th>
                        <th class="py-3 px-6 text-center">Status Deadline</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($meetings as $meeting)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}<br>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }}</span>
                        </td>
                        <td class="py-3 px-6">
                            <span class="font-bold block">{{ $meeting->type_of_meeting }}</span>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center">
                                <span class="font-medium">{{ $meeting->facilitator }}</span>
                            </div>
                            <div class="text-xs text-gray-400">{{ $meeting->location }}</div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-blue-100 text-blue-600 py-1 px-3 rounded-full text-xs">
                                {{ $meeting->actionItems->count() }} Item
                            </span>
                        </td>
                        
                        {{-- LOGIKA PERINGATAN DEADLINE --}}
                        <td class="py-3 px-6 text-center">
                            @if($meeting->hasDueDeadlines())
                                <div class="flex items-center justify-center text-red-600 font-bold animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    DEADLINE ALERT!
                                </div>
                                <div class="text-xs text-red-500 mt-1">Cek Action Items</div>
                            @else
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Aman
                                </span>
                            @endif
                        </td>

                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('meetings.edit', $meeting->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('meetings.pdf', $meeting->id) }}" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST" class="delete-form inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection