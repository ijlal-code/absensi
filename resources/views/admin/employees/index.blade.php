@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: { 50:'#fef2f2', 100:'#fee2e2', 500:'#ef4444', 600:'#dc2626', 700:'#b91c1c' }
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-[98%] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">Informasi Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500">Database Lengkap Semen Tonasa 2026</p>
            </div>
            <div class="mt-4 flex gap-2 md:mt-0">
                <form action="{{ route('employees.index') }}" method="GET" class="flex w-full max-w-lg gap-2">
                     <button type="submit" name="filter_birthday" value="today" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 text-sm whitespace-nowrap shadow-sm transition">
                        🎂 Ultah Hari Ini
                    </button>
                    <div class="relative rounded-md shadow-sm flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-600 sm:text-sm" 
                            placeholder="Cari NIK, SAP, Nama...">
                    </div>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">Cari</button>
                    <a href="{{ route('employees.index') }}" class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-50 flex items-center transition">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600">
            <div class="overflow-x-auto">
                <table class="min-w-max w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase sticky left-0 bg-primary-50 z-10 shadow-sm">Aksi</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">NIK</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">SAP</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Nama Pegawai</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Jabatan</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Direktorat</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Departemen</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Unit Kerja</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Subgroup</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Band</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Org Unit</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Cost Center Txt</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Email</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Masa Kerja</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Umur</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Tgl Masuk</th>
                            <th class="px-4 py-3 text-left font-bold text-primary-700 uppercase">Tgl Pensiun</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $emp)
                        @php
                            $isBirthday = $emp->tanggal_lahir && $emp->tanggal_lahir->format('m-d') == date('m-d');
                        @endphp
                        <tr class="{{ $isBirthday ? 'bg-green-50' : 'hover:bg-red-50 transition' }}">
                            <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white z-10 shadow-sm">
                                <button onclick="openModal('modal-{{ $emp->id }}')" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold py-1.5 px-4 rounded-full shadow transition transform hover:scale-105">
                                    Detail
                                </button>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $emp->nik ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $emp->sap_id ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col min-w-[200px]">
                                    <span class="font-bold text-gray-900">{{ $emp->nama }}</span>
                                    @if($isBirthday) <span class="text-xs text-green-600 font-bold animate-pulse">🎉 Ultah Hari Ini!</span> @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->jabatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->direktorat ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->departemen ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->unit_kerja ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $emp->subgroup ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-center">{{ $emp->band ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->organizational_unit ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 min-w-[150px]">{{ $emp->cost_center_text ?? '-' }}</td>
                            <td class="px-4 py-3 text-blue-600">{{ $emp->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-center">{{ $emp->masa_kerja ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-center">{{ $emp->umur ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $emp->tanggal_masuk ? $emp->tanggal_masuk->format('d M Y') : '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $emp->tanggal_pensiun ? $emp->tanggal_pensiun->format('d M Y') : '-' }}</td>
                        </tr>

                        <div id="modal-{{ $emp->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('modal-{{ $emp->id }}')"></div>

                            <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-4 sm:w-full sm:max-w-7xl border-t-8 border-primary-600">
                                    
                                    <div class="bg-white px-6 py-4 border-b flex justify-between items-center">
                                        <div>
                                            <h3 class="text-2xl font-bold leading-6 text-gray-900">Detail Lengkap Karyawan</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $emp->nama }} - {{ $emp->nik }}</p>
                                        </div>
                                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal('modal-{{ $emp->id }}')">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-6 bg-gray-50 h-[80vh] overflow-y-auto">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                            
                                            <div class="md:col-span-3 flex flex-col gap-6">
                                                
                                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="text-center mb-3">
                                                        <span class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1 rounded-full uppercase tracking-wider">Foto Terbaru</span>
                                                    </div>
                                                    <div class="aspect-[3/4] w-full bg-gray-100 rounded-md overflow-hidden flex items-center justify-center border border-gray-300">
                                                        @if(isset($emp->foto_terbaru) && $emp->foto_terbaru)
                                                            <img src="{{ asset('storage/'.$emp->foto_terbaru) }}" class="object-cover w-full h-full">
                                                        @else
                                                            <div class="text-gray-400 flex flex-col items-center">
                                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                <span class="text-xs">Tidak ada foto</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="text-center mb-3">
                                                        <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full uppercase tracking-wider">Foto Lama</span>
                                                    </div>
                                                    <div class="aspect-[3/4] w-full bg-gray-100 rounded-md overflow-hidden flex items-center justify-center border border-gray-300 opacity-90">
                                                        @if(isset($emp->foto_lama) && $emp->foto_lama)
                                                            <img src="{{ asset('storage/'.$emp->foto_lama) }}" class="object-cover w-full h-full grayscale hover:grayscale-0 transition">
                                                        @else
                                                            <div class="text-gray-400 flex flex-col items-center">
                                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                <span class="text-xs">Tidak ada foto</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="md:col-span-9 space-y-6">
                                                
                                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                                    <div class="px-6 py-3 border-b bg-gray-100">
                                                        <h4 class="text-md font-bold text-gray-800">🏢 Informasi Jabatan & Organisasi</h4>
                                                    </div>
                                                    <div class="p-4 grid grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                                                        <div><span class="block text-gray-500 text-xs">NIK</span> <span class="font-semibold">{{ $emp->nik }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">SAP ID</span> <span class="font-semibold">{{ $emp->sap_id }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Nama Lengkap</span> <span class="font-semibold">{{ $emp->nama }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Jabatan</span> <span class="font-semibold">{{ $emp->jabatan }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Unit Kerja (Biro)</span> <span class="font-semibold">{{ $emp->unit_kerja }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Departemen</span> <span class="font-semibold">{{ $emp->departemen }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Direktorat</span> <span class="font-semibold">{{ $emp->direktorat }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Subgroup</span> <span class="font-semibold">{{ $emp->subgroup }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Band</span> <span class="font-semibold">{{ $emp->band }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Organizational Unit</span> <span class="font-semibold">{{ $emp->organizational_unit }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Cost Center Text</span> <span class="font-semibold">{{ $emp->cost_center_text }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Masa Kerja</span> <span class="font-semibold">{{ $emp->masa_kerja }} Tahun</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Tanggal Masuk</span> <span class="font-semibold text-green-700">{{ $emp->tanggal_masuk ? $emp->tanggal_masuk->format('d M Y') : '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Tanggal Pensiun</span> <span class="font-semibold text-red-600">{{ $emp->tanggal_pensiun ? $emp->tanggal_pensiun->format('d M Y') : '-' }}</span></div>
                                                    </div>
                                                </div>

                                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                                    <div class="px-6 py-3 border-b bg-gray-100 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        <h4 class="text-md font-bold text-gray-800">Detail Teknis & Struktural</h4>
                                                    </div>
                                                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-y-4 gap-x-4 text-sm bg-gray-50">
                                                        <div><span class="block text-gray-500 text-xs">Position Code</span> <span class="font-mono font-semibold">{{ $emp->position_code ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Cost Center</span> <span class="font-mono font-semibold">{{ $emp->cost_ctr ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Personnel Area</span> <span class="font-semibold">{{ $emp->personnel_area ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">TXT SECT</span> <span class="font-semibold">{{ $emp->seksi ?? '-' }}</span></div>
                                                        
                                                        <div><span class="block text-gray-500 text-xs">Abrevation Position</span> <span class="font-semibold">{{ $emp->abrev_position ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">Abrevation Org</span> <span class="font-semibold">{{ $emp->abrev_organization ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">OBJ DEPT</span> <span class="font-mono font-semibold">{{ $emp->obj_dept ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">OBJ BIRO</span> <span class="font-mono font-semibold">{{ $emp->obj_biro ?? '-' }}</span></div>
                                                        
                                                        <div><span class="block text-gray-500 text-xs">OBJ SECT</span> <span class="font-mono font-semibold">{{ $emp->obj_sect ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-500 text-xs">OBJ GRP</span> <span class="font-mono font-semibold">{{ $emp->obj_grp ?? '-' }}</span></div>
                                                    </div>
                                                </div>

                                                <div class="bg-red-50 rounded-lg shadow-sm border border-red-200 overflow-hidden">
                                                    <div class="px-6 py-3 border-b border-red-200 bg-red-100 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        <h4 class="text-md font-bold text-red-800">Data Pribadi (Confidential)</h4>
                                                    </div>
                                                    <div class="p-4 grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 text-sm">
                                                        <div><span class="block text-gray-600 text-xs">Tanggal Lahir</span> <span class="font-bold text-gray-900">{{ $emp->tanggal_lahir ? $emp->tanggal_lahir->format('d M Y') : '-' }}</span></div>
                                                        <div><span class="block text-gray-600 text-xs">Umur</span> <span class="font-semibold">{{ $emp->umur ?? '-' }} Tahun</span></div>
                                                        <div><span class="block text-gray-600 text-xs">Jenis Kelamin</span> <span class="font-semibold">{{ $emp->jenis_kelamin ?? '-' }}</span></div>
                                                        
                                                        <div><span class="block text-gray-600 text-xs">Agama</span> <span class="font-semibold">{{ $emp->agama ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-600 text-xs">Tempat Lahir</span> <span class="font-semibold">{{ $emp->tempat_lahir ?? '-' }}</span></div>
                                                        <div><span class="block text-gray-600 text-xs">Pendidikan</span> <span class="font-semibold">{{ $emp->pendidikan ?? '-' }}</span></div>
                                                        
                                                        <div class="md:col-span-2">
                                                            <span class="block text-gray-600 text-xs">Email Pribadi/Kantor</span>
                                                            <span class="font-medium text-blue-700">{{ $emp->email ?? '-' }}</span>
                                                        </div>
                                                        
                                                        <div class="md:col-span-3">
                                                            <span class="block text-gray-600 text-xs mb-1">Alamat Domisili</span>
                                                            <div class="bg-white p-3 rounded border border-red-200 text-gray-800 text-xs leading-relaxed">
                                                                {{ $emp->alamat ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-6 py-4 border-t flex justify-end">
                                        <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="closeModal('modal-{{ $emp->id }}')">
                                            Tutup
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="17" class="px-6 py-10 text-center text-gray-500">Tidak ada data ditemukan.</td>
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