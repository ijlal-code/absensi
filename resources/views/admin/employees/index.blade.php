@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: { 50:'#fef2f2', 100:'#fee2e2', 500:'#ef4444', 600:'#dc2626', 700:'#b91c1c' },
                    success: { 50:'#f0fdf4', 100:'#dcfce7', 600:'#16a34a' } // Tambahan warna hijau
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
                <p class="mt-1 text-sm text-gray-500">Database lengkap pegawai aktif Tonasa.</p>
            </div>
            <div class="mt-4 flex flex-col md:flex-row gap-2 md:mt-0">
                
                <form action="{{ route('employees.index') }}" method="GET" class="flex w-full max-w-2xl gap-2">
                    
                    <button type="submit" name="filter_birthday" value="today" 
                            class="whitespace-nowrap bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 shadow-sm text-sm font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0l-1.5-.75m15 0V18a2.25 2.25 0 00-2.25-2.25h-1.312c-.921 0-1.822.304-2.592.837A6.37 6.37 0 0112 18a6.37 6.37 0 01-3.846-1.413 8.355 8.355 0 00-2.592-.837H4.25A2.25 2.25 0 002 18v3.75" />
                        </svg>
                        Ultah Hari Ini
                    </button>

                    <div class="relative rounded-md shadow-sm flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-600 sm:text-sm" 
                            placeholder="Cari NIK, Nama, atau Unit...">
                    </div>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">Cari</button>
                    
                    <a href="{{ route('employees.index') }}" class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-50" title="Refresh / Reset">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Email</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $emp)
                        @php
                            // Cek apakah hari ini ulang tahun
                            $isBirthday = false;
                            if($emp->tanggal_lahir) {
                                $isBirthday = \Carbon\Carbon::parse($emp->tanggal_lahir)->format('m-d') == \Carbon\Carbon::now()->format('m-d');
                            }
                        @endphp
                        
                        <tr class="{{ $isBirthday ? 'bg-success-50 hover:bg-success-100 ring-1 ring-inset ring-success-600' : 'hover:bg-red-50 transition' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="openModal('modal-{{ $emp->id }}')" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold py-1.5 px-4 rounded-full shadow transition transform hover:scale-105">
                                    Detail
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $emp->nik }}
                                @if($isBirthday) 
                                    <span class="ml-2 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">🎂 Ultah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full {{ $isBirthday ? 'bg-green-100 text-green-700 border-green-200' : 'bg-primary-100 text-primary-600 border-primary-200' }} flex items-center justify-center font-bold border mr-3">
                                        {{ substr($emp->nama, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $emp->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->jabatan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->unit_kerja }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $emp->email ?? '-' }}</td>
                        </tr>

                        <div id="modal-{{ $emp->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('modal-{{ $emp->id }}')"></div>

                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border-t-8 border-primary-600">
                                    
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b flex justify-between items-center">
                                        <div>
                                            <h3 class="text-2xl font-bold leading-6 text-gray-900">Detail Karyawan</h3>
                                            @if($isBirthday)
                                                <p class="text-green-600 font-bold text-sm mt-1">🎉 Sedang Berulang Tahun Hari Ini!</p>
                                            @endif
                                        </div>
                                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal('modal-{{ $emp->id }}')">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-6 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                            
                                            <div class="md:col-span-3 flex flex-col gap-4">
                                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="aspect-[3/4] w-full bg-gray-200 rounded-md overflow-hidden flex items-center justify-center">
                                                        <span class="text-4xl text-gray-400">👤</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-9">
                                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                                                    <div class="px-6 py-4 border-b bg-gray-50">
                                                        <h4 class="text-lg font-bold text-gray-900">Informasi Jabatan</h4>
                                                    </div>

                                                    <div class="divide-y text-sm border-b">
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Nama</span>
                                                            <span class="col-span-2 font-bold text-gray-900">{{ $emp->nama }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">NIK / SAP ID</span>
                                                            <span class="col-span-2">{{ $emp->nik }} / {{ $emp->sap_id ?? '-' }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Jabatan</span>
                                                            <span class="col-span-2">{{ $emp->jabatan }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Unit Kerja</span>
                                                            <span class="col-span-2">{{ $emp->unit_kerja }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="px-6 py-4 border-b bg-red-50 border-t-4 border-red-200 mt-2">
                                                        <h4 class="text-lg font-bold text-red-800 flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                            </svg>
                                                            Data Personal (Confidential)
                                                        </h4>
                                                        <p class="text-xs text-red-600">Data ini bersifat rahasia (Sesuai kolom merah Excel).</p>
                                                    </div>

                                                    <div class="divide-y text-sm bg-red-50/30">
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Tanggal Lahir</span>
                                                            <span class="col-span-2 font-semibold text-gray-900">
                                                                {{ $emp->tanggal_lahir ? \Carbon\Carbon::parse($emp->tanggal_lahir)->format('d M Y') : '-' }}
                                                            </span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Tempat Lahir</span>
                                                            <span class="col-span-2">{{ $emp->tempat_lahir ?? '-' }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Jenis Kelamin</span>
                                                            <span class="col-span-2">{{ $emp->jenis_kelamin }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Agama</span>
                                                            <span class="col-span-2">{{ $emp->agama ?? '-' }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Pendidikan Terakhir</span>
                                                            <span class="col-span-2">{{ $emp->pendidikan ?? '-' }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Alamat Domisili</span>
                                                            <span class="col-span-2">{{ $emp->alamat ?? '-' }}</span>
                                                        </div>
                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-700">Email Pribadi</span>
                                                            <span class="col-span-2 text-blue-600">{{ $emp->email ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t">
                                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeModal('modal-{{ $emp->id }}')">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p>Tidak ada data ditemukan.</p>
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
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    }
</script>
@endsection