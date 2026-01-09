@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: { 50:'#fef2f2', 100:'#fee2e2', 500:'#ef4444', 600:'#dc2626', 700:'#b91c1c' },
                    success: { 50:'#f0fdf4', 100:'#dcfce7', 600:'#16a34a' } 
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">Informasi Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500">Database Pegawai Semen Tonasa 2026</p>
            </div>
            
            <div class="mt-4 flex flex-col md:flex-row gap-2 md:mt-0">
                <form action="{{ route('employees.index') }}" method="GET" class="flex w-full max-w-3xl gap-2">
                    
                    <button type="submit" name="filter_birthday" value="today" 
                            class="whitespace-nowrap bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 shadow-sm text-sm font-bold flex items-center gap-2 transition">
                        <span>🎂</span> Ultah Hari Ini
                    </button>

                    <div class="relative rounded-md shadow-sm flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-600 sm:text-sm" 
                            placeholder="Cari Nama, NIK, atau Unit...">
                    </div>
                    
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 font-medium">Cari</button>
                    
                    <a href="{{ route('employees.index') }}" class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-50 flex items-center font-medium">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Aksi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Nama Pegawai</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Jabatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Unit Kerja</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $emp)
                        @php
                            $isBirthday = false;
                            if($emp->tanggal_lahir) {
                                $isBirthday = $emp->tanggal_lahir->format('m-d') == \Carbon\Carbon::now()->format('m-d');
                            }
                        @endphp
                        
                        <tr class="{{ $isBirthday ? 'bg-green-50 hover:bg-green-100 ring-1 ring-inset ring-green-500' : 'hover:bg-red-50 transition' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="openModal('modal-{{ $emp->id }}')" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold py-1.5 px-4 rounded-full shadow transition transform hover:scale-105">
                                    Detail
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $emp->nik }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full {{ $isBirthday ? 'bg-green-200 text-green-800' : 'bg-primary-100 text-primary-600' }} flex items-center justify-center font-bold border mr-3 shrink-0">
                                        {{ substr($emp->nama, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $emp->nama }}</span>
                                        @if($isBirthday) 
                                            <span class="text-[10px] uppercase font-bold text-green-600 bg-green-100 px-1.5 rounded w-fit mt-0.5">🎉 Ulang Tahun!</span> 
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->jabatan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->unit_kerja }}</td>
                        </tr>

                        <div id="modal-{{ $emp->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('modal-{{ $emp->id }}')"></div>

                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border-t-8 border-primary-600">
                                    
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b flex justify-between items-center">
                                        <div>
                                            <h3 class="text-2xl font-bold leading-6 text-gray-900">Detail Karyawan</h3>
                                            @if($isBirthday)
                                                <p class="text-green-600 font-bold text-sm mt-1 animate-pulse">🎉 Selamat Ulang Tahun Hari Ini!</p>
                                            @endif
                                        </div>
                                        <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeModal('modal-{{ $emp->id }}')">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-6 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            
                                            <div>
                                                <h4 class="font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    Informasi Pekerjaan
                                                </h4>
                                                <div class="space-y-3 text-sm">
                                                    <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                                                        <span class="text-gray-500 font-medium">Nama</span>
                                                        <span class="col-span-2 font-bold text-gray-900">{{ $emp->nama }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                                                        <span class="text-gray-500 font-medium">NIK</span>
                                                        <span class="col-span-2 text-gray-800">{{ $emp->nik }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                                                        <span class="text-gray-500 font-medium">SAP ID</span>
                                                        <span class="col-span-2 text-gray-800">{{ $emp->sap_id ?? '-' }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                                                        <span class="text-gray-500 font-medium">Jabatan</span>
                                                        <span class="col-span-2 text-gray-800">{{ $emp->jabatan }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 border-b border-gray-100 pb-2">
                                                        <span class="text-gray-500 font-medium">Unit Kerja</span>
                                                        <span class="col-span-2 text-gray-800">{{ $emp->unit_kerja }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <span class="text-gray-500 font-medium">Band</span>
                                                        <span class="col-span-2 text-gray-800">{{ $emp->band ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative bg-red-50 border border-red-200 rounded-xl p-5 shadow-sm">
                                                <div class="absolute -top-3 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                                    CONFIDENTIAL / RAHASIA
                                                </div>
                                                
                                                <h4 class="font-bold text-red-800 mb-4 border-b border-red-200 pb-2 mt-2 flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                    Data Personal
                                                </h4>
                                                
                                                <div class="space-y-3 text-sm">
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Tanggal Lahir:</span> 
                                                        <span class="font-bold text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200">
                                                            {{ $emp->tanggal_lahir ? $emp->tanggal_lahir->format('d M Y') : '-' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Jenis Kelamin:</span> 
                                                        <span class="font-medium text-gray-800">{{ $emp->jenis_kelamin }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Agama:</span> 
                                                        <span class="font-medium text-gray-800">{{ $emp->agama ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Tempat Lahir:</span> 
                                                        <span class="font-medium text-gray-800">{{ $emp->tempat_lahir ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Pendidikan:</span> 
                                                        <span class="font-medium text-gray-800">{{ $emp->pendidikan ?? '-' }}</span>
                                                    </div>
                                                     <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Email:</span> 
                                                        <span class="font-medium text-blue-600 truncate max-w-[200px]">{{ $emp->email ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center border-b border-red-100 pb-2">
                                                        <span class="text-gray-600">Tanggal Masuk:</span> 
                                                        <span class="font-medium text-gray-800">{{ $emp->tanggal_masuk ? $emp->tanggal_masuk->format('d M Y') : '-' }}</span>
                                                    </div>
                                                    <div class="pt-2">
                                                        <span class="block text-gray-600 text-xs mb-1">Alamat:</span>
                                                        <p class="text-gray-900 bg-white p-2 rounded border border-red-200 text-xs leading-relaxed">
                                                            {{ $emp->alamat ?? '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t">
                                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeModal('modal-{{ $emp->id }}')">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="text-lg font-medium">Tidak ada data ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t">
                {{ $employees->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection