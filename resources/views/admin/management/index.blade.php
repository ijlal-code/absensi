@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="w-full md:w-auto">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Karyawan</h2>
            <p class="text-gray-600 text-sm">Kelola data karyawan Semen Tonasa.</p>
        </div>
        
        {{-- Tombol Action --}}
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
             <a href="{{ route('employee-management.stats') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center w-full sm:w-auto">
                <i class="fas fa-chart-pie mr-2"></i>Statistik
            </a>
            <a href="{{ route('employee-management.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i>Tambah Karyawan
            </a>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('employee-management.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Cari berdasarkan Nama, NIK, atau SAP ID...">
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition w-full md:w-auto flex justify-center items-center">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('employee-management.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center justify-center w-full md:w-auto">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Jabatan & Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Masa Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Kontak (Utama)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($employee->foto_terbaru)
                                            <img class="h-10 w-10 rounded-full object-cover border" src="{{ asset('storage/' . $employee->foto_terbaru) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-bold">
                                                {{ substr($employee->nama, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->nama }}</div>
                                        {{-- PERUBAHAN DI SINI: Menampilkan SAP dan NIK --}}
                                        <div class="text-xs text-gray-500">
                                            SAP: {{ $employee->sap_id }} <span class="mx-1">|</span> NIK: {{ $employee->nik }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{Str::limit($employee->jabatan, 20)}}</div>
                                <div class="text-xs text-gray-500">{{ $employee->unit_kerja }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $employee->masa_kerja }} Tahun
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $primaryField = $employee->primary_phone ?? 'no_hp_1'; 
                                    $primaryNumber = $employee->$primaryField;
                                @endphp
                                
                                @if($primaryNumber)
                                    <span class="flex items-center text-blue-600 font-medium">
                                        <i class="fas fa-phone-alt text-xs mr-2"></i> {{ $primaryNumber }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">
                                        Tidak ada ({{ str_replace('_', ' ', strtoupper($primaryField)) }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('employee-management.edit', $employee->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- PERUBAHAN DI SINI: Update tombol hapus pakai SweetAlert (delete-form) --}}
                                    <form action="{{ route('employee-management.destroy', $employee->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Data karyawan tidak ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
@endsection