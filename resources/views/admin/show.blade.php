@extends('layouts.admin')
@section('title', 'Monitoring: ' . $event->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600"><i class="fas fa-arrow-left"></i> Kembali</a>
    <div class="space-x-2">
        <a href="{{ route('attendance.pdf', $event->id) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
            <i class="fas fa-file-pdf mr-1"></i> Download PDF
        </a>
        {{-- Jika mau tambah Excel nanti: --}}
        {{-- <a href="#" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm"><i class="fas fa-file-excel"></i> Excel</a> --}}
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Absen</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Kerja</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $attendance->created_at->format('H:i:s') }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold">
                    {{ $attendance->name }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $attendance->work_unit }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                    @if($attendance->signature_path)
                        <img src="{{ asset('storage/' . $attendance->signature_path) }}" class="h-10 inline-block border rounded">
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-gray-500">Belum ada data absensi masuk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection