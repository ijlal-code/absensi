@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Karyawan</h2>
        <a href="{{ route('employee-management.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Karyawan
        </a>
    </div>

    {{-- Search Form dengan Tombol Reset --}}
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border">
        <form method="GET" action="{{ route('employee-management.index') }}">
            <div class="flex flex-col md:flex-row gap-2">
                <div class="relative w-full md:w-1/2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-50 border rounded-lg focus:outline-none focus:border-blue-500" 
                           placeholder="Cari SAP atau Nama..." 
                           onchange="this.form.submit()">
                </div>
                
                {{-- Tombol Reset --}}
                @if(request('search'))
                    <a href="{{ route('employee-management.index') }}" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-medium flex items-center">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Ringkas --}}
    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    {{-- PERUBAHAN DI SINI: Header ditukar jadi SAP ID / NIK --}}
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider w-1/4">SAP / NIK</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Nama Karyawan</th>
                    <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-1/6">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{-- PERUBAHAN DI SINI: SAP ID jadi utama (tebal), NIK jadi kecil di bawahnya --}}
                        <span class="font-mono font-semibold">{{ $employee->sap_id ?? '-' }}</span>
                        <div class="text-xs text-gray-400">{{ $employee->nik ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                        {{ $employee->nama }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('employee-management.edit', $employee->id) }}" class="text-blue-600 hover:text-blue-900 tooltip" title="Edit Data Lengkap">
                                <i class="fas fa-edit text-xl"></i>
                            </a>
                            <form action="{{ route('employee-management.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data {{ $employee->nama }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 tooltip" title="Hapus Data">
                                    <i class="fas fa-trash-alt text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">
                        Data karyawan tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION CUSTOM BAHASA INDONESIA --}}
<div class="mt-6 bg-white px-4 py-3 border rounded-lg flex items-center justify-between shadow-sm">
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-600">
                Menampilkan <span class="font-bold text-gray-800">{{ $employees->firstItem() ?? 0 }}</span> 
                sampai <span class="font-bold text-gray-800">{{ $employees->lastItem() ?? 0 }}</span> 
                dari <span class="font-bold text-gray-800">{{ $employees->total() }}</span> data
            </p>
        </div>
        <div>
            @if ($employees->hasPages())
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{-- Tombol Sebelumnya --}}
                    @if ($employees->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                        </span>
                    @else
                        <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left mr-2 text-blue-600"></i> Sebelumnya
                        </a>
                    @endif

                    {{-- Tombol Selanjutnya --}}
                    @if ($employees->hasMorePages())
                        <a href="{{ $employees->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Selanjutnya <i class="fas fa-chevron-right ml-2 text-blue-600"></i>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                            Selanjutnya <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
</div>
</div>
@endsection