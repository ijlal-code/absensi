@extends('layouts.admin')
@section('title', 'Monitoring: ' . $event->title)

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
    </a>

    <div class="flex space-x-2">
        <a href="{{ route('attendance.pdf', $event->id) }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 text-sm transition">
            <i class="fas fa-file-pdf mr-1"></i> Download PDF
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-4 mb-6 border-l-4 border-blue-500">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div><span class="text-gray-500 block">Waktu:</span> {{ $event->start_time }} - {{ $event->end_time }}</div>
        <div><span class="text-gray-500 block">Lokasi:</span> {{ $event->location }}</div>
        <div><span class="text-gray-500 block">Total Hadir:</span> {{ $attendances->count() }} Orang</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Absen</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Kerja</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $attendance)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
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
                            <div class="relative group inline-block">
                                <img src="{{ asset('storage/' . $attendance->signature_path) }}" class="h-10 border rounded bg-white p-1">
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                    <img src="{{ asset('storage/' . $attendance->signature_path) }}" class="h-32 bg-white border shadow-lg rounded">
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500 bg-gray-50">
                        <i class="fas fa-users-slash text-4xl mb-2 text-gray-300 block"></i>
                        Belum ada data absensi masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection