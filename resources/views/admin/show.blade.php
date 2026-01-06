@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Monitoring: {{ $event->title }}</h2>
    
    <div class="flex space-x-2">
        <a href="{{ route('attendance.pdf', $event->id) }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 text-sm transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Download PDF
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-900">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        <div>
            <span class="text-gray-500 block uppercase text-xs font-bold">Tanggal</span> 
            {{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM Y') }}
        </div>
        <div>
            <span class="text-gray-500 block uppercase text-xs font-bold">Waktu</span> 
            {{ $event->start_time }} - {{ $event->end_time }}
        </div>
        <div>
            <span class="text-gray-500 block uppercase text-xs font-bold">Lokasi</span> 
            {{ $event->location }}
        </div>
        <div>
            <span class="text-gray-500 block uppercase text-xs font-bold">Total Hadir</span> 
            <span class="text-blue-600 font-bold text-lg">{{ $attendances->count() }}</span> Orang
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Kerja</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $attendance)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-500">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-500">
                        {{ $attendance->created_at->format('H:i') }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm font-bold text-gray-800">
                        {{ $attendance->name }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-600">
                        {{ $attendance->work_unit }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                        @if($attendance->signature_path)
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/' . $attendance->signature_path) }}" class="h-12 border rounded bg-white p-1 shadow-sm hover:scale-150 transition-transform duration-200 cursor-zoom-in">
                            </div>
                        @else
                            <span class="text-gray-400 italic text-xs">Tidak ada</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-500 bg-gray-50">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada peserta yang mengisi absensi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection