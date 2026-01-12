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
        
        {{-- HEADER --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">Informasi Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500">Database Lengkap Semen Tonasa 2026</p>
            </div>
            
            {{-- SEARCH FORM --}}
            <div class="mt-4 flex gap-2 md:mt-0">
                <div class="flex w-full max-w-lg gap-2">
                     <form action="{{ route('employees.index') }}" method="GET" class="contents">
                        <button type="submit" name="filter_birthday" value="today" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 text-sm whitespace-nowrap shadow-sm transition">
                            Ultah Hari Ini
                        </button>
                    </form>
                    
                    <div class="relative rounded-md shadow-sm flex-grow">
                        <input type="text" id="live-search-input" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-600 sm:text-sm" 
                            placeholder="Cari NIK, Nama, SAP..." autocomplete="off">
                        
                        <div id="loading-indicator" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <a href="{{ route('employees.index') }}" class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-50 flex items-center transition">
                        Reset
                    </a>
                </div>
            </div>
        </div>

        {{-- WRAPPER KONTEN --}}
        <div id="employee-content-wrapper">
            <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600">
                <div class="overflow-x-auto">
                    <table class="min-w-max w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase sticky left-0 bg-primary-50 z-10 shadow-sm">Aksi</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">SAP</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">NIK</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Nama Karyawan</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Emp. Subgroup</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">TXT_DIR</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">TXT_DEPT</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">TXT_BIRO</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Birth Date</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Gender</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Org. Unit</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Cost Center</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Date Terminasi</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">E-mail</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Religious</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Umur</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Tempat Lahir</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Pendidikan</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Organilk</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">s.d</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Masa Kerja</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Alamat</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Band</th>
                                {{-- Header No HP Utama (Hanya 1 kolom di tabel) --}}
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">No. HP Utama</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($employees as $emp)
                            @php
                                // Logika Ulang Tahun
                                $isBirthday = $emp->tanggal_lahir && $emp->tanggal_lahir->format('m-d') == date('m-d');
                                
                                // Format Tanggal
                                $tglLahir = $emp->tanggal_lahir ? $emp->tanggal_lahir->format('d M Y') : '-';
                                $tglMasuk = $emp->tanggal_masuk ? $emp->tanggal_masuk->format('d M Y') : '-';
                                $tglPensiun = $emp->tanggal_pensiun ? $emp->tanggal_pensiun->format('d M Y') : '-';
                                
                                // JSON DATA
                                $jsonData = json_encode([
                                    'sap' => $emp->sap_id,
                                    'nik' => $emp->nik,
                                    'nama' => $emp->nama,
                                    
                                    // Kirim 3 Nomor HP untuk Modal
                                    'hp1' => $emp->no_hp_1, 
                                    'hp2' => $emp->no_hp_2, 
                                    'hp3' => $emp->no_hp_3, 

                                    'subgroup' => $emp->subgroup,
                                    'txt_dir' => $emp->direktorat,
                                    'txt_dept' => $emp->departemen,
                                    'txt_biro' => $emp->unit_kerja,
                                    'birth_date' => $tglLahir,
                                    'gender' => $emp->jenis_kelamin,
                                    'org_unit' => $emp->organizational_unit,
                                    'cost_center_text' => $emp->cost_center_text,
                                    'terminasi' => $tglPensiun,
                                    'email' => $emp->email,
                                    'religious' => $emp->agama,
                                    'umur' => $emp->umur,
                                    'tempat_lahir' => $emp->tempat_lahir,
                                    'pendidikan' => $emp->pendidikan,
                                    'organilk' => $tglMasuk,
                                    'sd' => $tglMasuk, 
                                    'masa_kerja' => $emp->masa_kerja,
                                    'alamat' => $emp->alamat,
                                    'band' => $emp->band,
                                    
                                    // Field Teknis
                                    'position' => $emp->jabatan,
                                    'cost_ctr' => $emp->cost_ctr,
                                    'txt_sect' => $emp->txt_sect, 
                                    'pers_area' => $emp->personnel_area,
                                    'abrev_pos' => $emp->abrev_position,
                                    'abrev_org' => $emp->abrev_organization,
                                    'obj_dept' => $emp->obj_dept,
                                    'obj_biro' => $emp->obj_biro,
                                    'obj_sect' => $emp->obj_sect,
                                    'obj_grp' => $emp->obj_grp,
                                    
                                    // Foto
                                    'foto_baru' => $emp->foto_terbaru ? asset('storage/'.$emp->foto_terbaru) : null,
                                    'foto_lama' => $emp->foto_lama ? asset('storage/'.$emp->foto_lama) : null,
                                ]);
                            @endphp

                            {{-- LOGIKA WARNA BARIS: Hijau Full jika Ultah, Putih jika tidak --}}
                            <tr class="{{ $isBirthday ? 'bg-green-100 text-green-900' : 'bg-white hover:bg-gray-50 transition' }}">
                                
                                {{-- Tombol Detail (Sticky) --}}
                                <td class="px-2 py-3 whitespace-nowrap sticky left-0 z-10 shadow-sm border-r {{ $isBirthday ? 'bg-green-100' : 'bg-white' }}">
                                    <button onclick="showEmployeeModal(this)" 
                                            data-json="{{ $jsonData }}"
                                            class="bg-primary-600 hover:bg-primary-700 text-white text-[10px] font-bold py-1 px-3 rounded shadow transition">
                                        Detail
                                    </button>
                                </td>

                                {{-- DATA TABEL --}}
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->sap_id ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap font-medium">{{ $emp->nik ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap font-bold flex items-center gap-1">
                                    {{ $emp->nama ?? '-' }}
                                    @if($isBirthday) <span>🎂</span> @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->subgroup ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->direktorat ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->departemen ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->unit_kerja ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglLahir }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->jenis_kelamin ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->organizational_unit ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->cost_center_text ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-red-600">{{ $tglPensiun }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-blue-600">{{ $emp->email ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->agama ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->umur ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->tempat_lahir ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->pendidikan ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglMasuk }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglMasuk }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->masa_kerja ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap truncate max-w-[150px]">{{ $emp->alamat ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->band ?? '-' }}</td>
                                
                                {{-- KOLOM NO HP UTAMA (Tampil di tabel) --}}
                                <td class="px-2 py-2 whitespace-nowrap font-medium text-gray-700">
                                    {{ $emp->no_hp_1 ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="25" class="px-6 py-10 text-center text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- PAGINATION MINIMALIS --}}
                <div class="bg-white px-4 py-3 border-t flex items-center justify-between sm:px-6">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs text-gray-500">
                                Menampilkan <span class="font-bold text-gray-700">{{ $employees->firstItem() ?? 0 }}</span> 
                                sampai <span class="font-bold text-gray-700">{{ $employees->lastItem() ?? 0 }}</span> 
                                dari <span class="font-bold text-gray-700">{{ $employees->total() }}</span> data
                            </p>
                        </div>
                        <div>
                            @if ($employees->hasPages())
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    @if ($employees->onFirstPage())
                                        <span class="relative inline-flex items-center px-3 py-1.5 rounded-l-md border border-gray-300 bg-gray-50 text-xs font-medium text-gray-400 cursor-not-allowed">&laquo; Sebelumnya</span>
                                    @else
                                        <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 rounded-l-md border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 transition">&laquo; Sebelumnya</a>
                                    @endif

                                    @if ($employees->hasMorePages())
                                        <a href="{{ $employees->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 rounded-r-md border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 transition">Selanjutnya &raquo;</a>
                                    @else
                                        <span class="relative inline-flex items-center px-3 py-1.5 rounded-r-md border border-gray-300 bg-gray-50 text-xs font-medium text-gray-400 cursor-not-allowed">Selanjutnya &raquo;</span>
                                    @endif
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SINGLE MODAL TEMPLATE --}}
<div id="single-employee-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeEmployeeModal()"></div>

    <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-4 sm:w-full sm:max-w-6xl border-t-8 border-primary-600">
            
            {{-- Modal Header --}}
            <div class="bg-white px-6 py-4 border-b flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h3 class="text-2xl font-bold leading-6 text-gray-900">Kartu Data Karyawan</h3>
                    <p class="text-sm text-gray-500 mt-1" id="modal-header-sub">Nama - NIK</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeEmployeeModal()">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="px-6 py-6 bg-gray-50 h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- KIRI: FOTO --}}
                    <div class="lg:col-span-3 flex flex-col gap-4">
                        <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                            <div class="text-center mb-2"><span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded uppercase">Foto Terbaru</span></div>
                            <div class="aspect-[3/4] w-full bg-gray-100 rounded overflow-hidden flex items-center justify-center border border-gray-300">
                                <img id="img-foto-baru" src="" class="object-cover w-full h-full hidden">
                                <span id="no-foto-baru" class="text-xs text-gray-400 hidden">Tidak ada foto</span>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                            <div class="text-center mb-2"><span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded uppercase">Foto Lama</span></div>
                            <div class="aspect-[3/4] w-full bg-gray-100 rounded overflow-hidden flex items-center justify-center border border-gray-300 opacity-90">
                                <img id="img-foto-lama" src="" class="object-cover w-full h-full grayscale hover:grayscale-0 transition hidden">
                                <span id="no-foto-lama" class="text-xs text-gray-400 hidden">Tidak ada foto</span>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: SEMUA DATA DIGABUNG --}}
                    <div class="lg:col-span-9">
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="px-6 py-3 bg-primary-50 border-b border-primary-100 flex items-center">
                                <h4 class="text-lg font-bold text-gray-800">Data Lengkap Karyawan</h4>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                                    
                                    {{-- List Field --}}
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">SAP / NIK</p><p class="font-mono font-bold text-gray-900"><span id="d-sap">-</span> / <span id="d-nik">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Nama Karyawan</p><p class="font-bold text-gray-900 text-base" id="d-nama">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Employee Subgroup</p><p class="font-semibold text-gray-800" id="d-subgroup">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Position / Band</p><p class="font-semibold text-gray-800"><span id="d-position">-</span> (<span id="d-band">-</span>)</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_DIR</p><p class="font-semibold text-gray-800" id="d-txt-dir">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_DEPT</p><p class="font-semibold text-gray-800" id="d-txt-dept">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_BIRO</p><p class="font-semibold text-gray-800" id="d-txt-biro">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_SECT</p><p class="font-semibold text-gray-800" id="d-txt-sect">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Organizational Unit</p><p class="font-semibold text-gray-800" id="d-org-unit">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Cost Center (Text)</p><p class="font-semibold text-gray-800" id="d-cost-txt">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Cost Ctr (Code)</p><p class="font-mono font-semibold text-gray-800" id="d-cost-ctr">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Personnel Area</p><p class="font-semibold text-gray-800" id="d-pers-area">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Obj Dept / Obj Biro</p><p class="font-mono text-gray-800 text-xs"><span id="d-obj-dept">-</span> / <span id="d-obj-biro">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Obj Sect / Obj Grp</p><p class="font-mono text-gray-800 text-xs"><span id="d-obj-sect">-</span> / <span id="d-obj-grp">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Abrev. Position</p><p class="font-semibold text-gray-800" id="d-abrev-pos">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Abrev. Organization</p><p class="font-semibold text-gray-800" id="d-abrev-org">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Birth Date / Umur</p><p class="font-semibold text-gray-800"><span id="d-birth">-</span> (<span id="d-umur">-</span> Thn)</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Tempat Lahir</p><p class="font-semibold text-gray-800" id="d-tmplahir">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Gender Key</p><p class="font-semibold text-gray-800" id="d-gender">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Religious</p><p class="font-semibold text-gray-800" id="d-religion">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Date Terminasi</p><p class="font-bold text-red-600" id="d-terminasi">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Organilk / s.d</p><p class="font-bold text-green-700"><span id="d-organilk">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Masa Kerja</p><p class="font-semibold text-gray-800"><span id="d-masa">-</span> Tahun</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">E-mail</p><p class="font-semibold text-blue-600 break-all" id="d-email">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1 md:col-span-2"><p class="text-xs text-gray-400 mb-0.5">Pendidikan</p><p class="font-semibold text-gray-800" id="d-pendidikan">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1 md:col-span-2"><p class="text-xs text-gray-400 mb-0.5">Alamat</p><p class="font-medium text-gray-800 bg-gray-50 p-2 rounded block w-full text-xs" id="d-alamat">-</p></div>

                                    {{-- TAMPILAN 3 NOMOR HP DI MODAL --}}
                                    <div class="group border-b border-gray-50 pb-2 md:col-span-2">
                                        <p class="text-xs text-gray-400 mb-1">Kontak Telepon / HP</p>
                                        <div class="flex flex-wrap gap-8 bg-gray-50 p-2 rounded border border-gray-100">
                                            <div>
                                                <span class="text-[10px] text-gray-400 uppercase tracking-wider block">HP Utama</span>
                                                <span class="font-bold text-green-700 text-sm" id="d-hp1">-</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-gray-400 uppercase tracking-wider block">HP Kedua</span>
                                                <span class="font-semibold text-gray-600 text-sm" id="d-hp2">-</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-gray-400 uppercase tracking-wider block">HP Ketiga</span>
                                                <span class="font-semibold text-gray-600 text-sm" id="d-hp3">-</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end">
                <button type="button" class="bg-white py-2 px-6 border border-gray-300 rounded-md shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-100 focus:outline-none" onclick="closeEmployeeModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('single-employee-modal');
    
    // Fungsi pembantu: Jika value kosong/null/undefined, tampilkan "-"
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if(el) {
            // Cek jika value null, undefined, atau string kosong
            el.textContent = (value && value.toString().trim() !== "") ? value : '-';
        }
    };

    function showEmployeeModal(btn) {
        const data = JSON.parse(btn.getAttribute('data-json'));
        
        document.getElementById('modal-header-sub').textContent = `${data.nama || '-'} - ${data.nik || '-'}`;

        const handleImage = (imgId, noImgId, src) => {
            const imgEl = document.getElementById(imgId);
            const noImgEl = document.getElementById(noImgId);
            if(src) {
                imgEl.src = src;
                imgEl.classList.remove('hidden');
                noImgEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                noImgEl.classList.remove('hidden');
            }
        };
        handleImage('img-foto-baru', 'no-foto-baru', data.foto_baru);
        handleImage('img-foto-lama', 'no-foto-lama', data.foto_lama);

        // Populate Data Text
        setText('d-sap', data.sap);
        setText('d-nik', data.nik);
        setText('d-nama', data.nama);
        
        // HP
        setText('d-hp1', data.hp1);
        setText('d-hp2', data.hp2);
        setText('d-hp3', data.hp3);

        setText('d-subgroup', data.subgroup);
        setText('d-position', data.position);
        setText('d-band', data.band);
        setText('d-txt-dir', data.txt_dir);
        setText('d-txt-dept', data.txt_dept);
        setText('d-txt-biro', data.txt_biro);
        setText('d-txt-sect', data.txt_sect);
        setText('d-org-unit', data.org_unit);
        setText('d-cost-txt', data.cost_center_text);
        setText('d-cost-ctr', data.cost_ctr);
        setText('d-pers-area', data.pers_area);
        setText('d-obj-dept', data.obj_dept);
        setText('d-obj-biro', data.obj_biro);
        setText('d-obj-sect', data.obj_sect);
        setText('d-obj-grp', data.obj_grp);
        setText('d-abrev-pos', data.abrev_pos);
        setText('d-abrev-org', data.abrev_org);
        setText('d-birth', data.birth_date);
        setText('d-umur', data.umur);
        setText('d-tmplahir', data.tempat_lahir);
        setText('d-gender', data.gender);
        setText('d-religion', data.religious);
        setText('d-terminasi', data.terminasi);
        setText('d-organilk', data.organilk);
        setText('d-masa', data.masa_kerja);
        setText('d-email', data.email);
        setText('d-pendidikan', data.pendidikan);
        setText('d-alamat', data.alamat);

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEmployeeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Logic Live Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('live-search-input');
        const contentWrapper = document.getElementById('employee-content-wrapper');
        const loadingIndicator = document.getElementById('loading-indicator');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            loadingIndicator.classList.remove('hidden');
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value;
                const url = `{{ route('employees.index') }}?search=${encodeURIComponent(query)}`;
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('employee-content-wrapper').innerHTML;
                    contentWrapper.innerHTML = newContent;
                    loadingIndicator.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingIndicator.classList.add('hidden');
                });
            }, 500);
        });
    });
</script>
@endsection