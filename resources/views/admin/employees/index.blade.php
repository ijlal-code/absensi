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

<div class="min-h-screen bg-gray-50 py-4 sm:py-8 font-sans">
    <div class="w-full max-w-[98%] mx-auto px-2 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Informasi Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500">Database Lengkap Semen Tonasa 2026</p>
            </div>
            
            {{-- SEARCH FORM & FILTER --}}
            <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">
                <div class="flex-none w-full md:w-auto">
                    <form action="{{ route('employees.index') }}" method="GET">
                        <button type="submit" name="filter_birthday" value="today" 
                            class="group inline-flex items-center justify-center gap-2 bg-white border border-green-600 text-green-700 px-4 py-2.5 rounded-lg hover:bg-green-50 transition font-medium text-sm shadow-sm w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Ultah Hari Ini</span>
                        </button>
                    </form>
                </div>

                <div class="hidden md:block h-8 w-[3px] bg-gray-300 rounded-full"></div>

                <div class="flex flex-col sm:flex-row gap-2 w-full md:max-w-md">
                    <div class="relative flex-grow w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" id="live-search-input" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                            placeholder="Cari SAP, Nama, NIK" autocomplete="off">
                        
                        <div id="loading-indicator" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                            <svg class="animate-spin h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <a href="{{ route('employees.index') }}" class="flex-none w-full sm:w-auto bg-gray-100 text-gray-600 border border-gray-300 px-4 py-2.5 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition font-medium text-sm shadow-sm flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </div>
        </div>

        {{-- WRAPPER KONTEN --}}
        <div id="employee-content-wrapper" class="relative z-0"> {{-- Tambahan z-0 untuk memastikan stacking context benar --}}
            <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600 flex flex-col">
                
                {{-- TABLE WRAPPER --}}
                <div class="overflow-x-auto w-full">
                    <table class="min-w-max w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase sticky left-0 bg-primary-50 z-10 shadow-sm border-r border-primary-100">Aksi</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">SAP</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">NIK</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">Nama Karyawan</th>
                                <th class="px-2 py-3 text-left font-bold text-primary-700 uppercase whitespace-nowrap">No. HP Utama</th>
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
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($employees as $emp)
                            @php
                                $isBirthday = $emp->tanggal_lahir && $emp->tanggal_lahir->format('m-d') == date('m-d');
                                
                                $tglLahir     = $emp->tanggal_lahir ? $emp->tanggal_lahir->format('d M Y') : '-';
                                $tglMasuk     = $emp->tanggal_masuk ? $emp->tanggal_masuk->format('d M Y') : '-';
                                $tglSd        = $emp->s_d ? $emp->s_d->format('d M Y') : '-';
                                $tglTerminasi = $emp->date_terminasi ? $emp->date_terminasi->format('d M Y') : '-';
                                
                                $primaryKey = $emp->primary_phone ?? 'no_hp_1';
                                $displayPhone = $emp->$primaryKey;

                                $jsonData = json_encode([
                                    'sap' => $emp->sap_id,
                                    'nik' => $emp->nik,
                                    'nama' => $emp->nama,
                                    'hp1' => $emp->no_hp_1, 
                                    'hp2' => $emp->no_hp_2, 
                                    'hp3' => $emp->no_hp_3,
                                    'primary_phone_key' => $primaryKey,
                                    'subgroup' => $emp->subgroup,
                                    'txt_dir' => $emp->direktorat,
                                    'txt_dept' => $emp->departemen,
                                    'txt_biro' => $emp->unit_kerja,
                                    'txt_sect' => $emp->seksi,
                                    'birth_date' => $tglLahir,
                                    'gender' => $emp->jenis_kelamin,
                                    'org_unit' => $emp->organizational_unit,
                                    'cost_center_text' => $emp->cost_center_text,
                                    'cost_ctr' => $emp->cost_ctr,
                                    'terminasi' => $tglTerminasi,
                                    'email' => $emp->email,
                                    'religious' => $emp->agama,
                                    'umur' => $emp->umur,
                                    'tempat_lahir' => $emp->tempat_lahir,
                                    'pendidikan' => $emp->pendidikan,
                                    'organilk' => $tglMasuk,
                                    'sd' => $tglSd,
                                    'masa_kerja' => $emp->masa_kerja,
                                    'alamat' => $emp->alamat,
                                    'band' => $emp->band,
                                    'position' => $emp->jabatan,
                                    'pers_area' => $emp->personnel_area,
                                    'abrev_pos' => $emp->abrev_position,
                                    'abrev_org' => $emp->abrev_organization,
                                    'obj_dept' => $emp->obj_dept,
                                    'obj_biro' => $emp->obj_biro,
                                    'obj_sect' => $emp->obj_sect,
                                    'obj_grp' => $emp->obj_grp,
                                    'foto_baru' => $emp->foto_terbaru ? asset('storage/'.$emp->foto_terbaru) : null,
                                    'foto_lama' => $emp->foto_lama ? asset('storage/'.$emp->foto_lama) : null,
                                ]);
                            @endphp

                            <tr class="{{ $isBirthday ? 'bg-green-100 text-green-900' : 'bg-white hover:bg-gray-50 transition' }}">
                                <td class="px-2 py-3 whitespace-nowrap sticky left-0 z-10 shadow-sm border-r border-gray-200 {{ $isBirthday ? 'bg-green-100' : 'bg-white' }}">
                                    <button onclick="showEmployeeModal(this)" 
                                            data-json="{{ $jsonData }}"
                                            class="bg-primary-600 hover:bg-primary-700 text-white text-[10px] font-bold py-1 px-3 rounded shadow transition">
                                            Detail
                                    </button>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->sap_id ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap font-medium">{{ $emp->nik ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap font-bold flex items-center gap-1">{{ $emp->nama ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap font-bold text-blue-700">
                                    {{ $displayPhone ? wordwrap($displayPhone, 4, ' ', true) : '-' }}
                                    @if($displayPhone)
                                        <span class="text-[9px] text-gray-400 block font-normal">
                                            ({{ $primaryKey == 'no_hp_1' ? 'HP 1' : ($primaryKey == 'no_hp_2' ? 'HP 2' : 'HP 3') }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->subgroup ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->direktorat ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->departemen ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->unit_kerja ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglLahir }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->jenis_kelamin ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->organizational_unit ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->cost_center_text ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-red-600">{{ $tglTerminasi }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-blue-600">{{ $emp->email ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->agama ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->umur ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->tempat_lahir ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $emp->pendidikan ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglMasuk }}</td>
                                <td class="px-2 py-2 whitespace-nowrap">{{ $tglSd }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->masa_kerja ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap truncate max-w-[150px]">{{ $emp->alamat ?? '-' }}</td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">{{ $emp->band ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="25" class="px-6 py-10 text-center text-gray-500">
                                    <p class="text-base">Tidak ada data ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- PAGINATION --}}
                <div class="bg-white px-4 py-3 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="w-full sm:w-auto text-center sm:text-left">
                            <p class="text-xs text-gray-700">
                                Menampilkan <span class="font-bold">{{ $employees->firstItem() ?? 0 }}</span> 
                                sampai <span class="font-bold">{{ $employees->lastItem() ?? 0 }}</span> 
                                dari <span class="font-bold">{{ $employees->total() }}</span> data
                            </p>
                        </div>
                        <div class="w-full sm:w-auto flex justify-center sm:justify-end" id="pagination-links">
                            @if ($employees->hasPages())
                                {{ $employees->links('pagination::tailwind') }}
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.employees.show_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('live-search-input');
        const contentWrapper = document.getElementById('employee-content-wrapper');
        const loadingIndicator = document.getElementById('loading-indicator');
        let timeout = null;

        // --- FUNGSI UTAMA AJAX FETCHER ---
        // Digunakan oleh Search dan Pagination agar konsisten
        function fetchEmployees(url) {
            loadingIndicator.classList.remove('hidden');
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Ambil konten baru
                    const newContent = doc.getElementById('employee-content-wrapper');
                    
                    if(newContent) {
                        contentWrapper.innerHTML = newContent.innerHTML;
                    }
                    
                    loadingIndicator.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingIndicator.classList.add('hidden');
                });
        }

        // 1. EVENT LISTENER UNTUK SEARCH (Debounce)
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value;
                const url = `{{ route('employees.index') }}?search=${encodeURIComponent(query)}`;
                fetchEmployees(url);
            }, 500);
        });

        // 2. EVENT LISTENER UNTUK PAGINATION (Delegation)
        // Ini kuncinya: Menangkap klik pada link pagination, mencegah default behaviour (reload),
        // dan mencegah event bubbling ke layout (sidebar)
        contentWrapper.addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah link pagination (tag <a> atau elemen di dalamnya)
            const link = e.target.closest('a'); // Cari tag <a> terdekat
            
            // Pastikan link tersebut ada di dalam area pagination (biasanya nav)
            // Class 'relative inline-flex items-center' adalah ciri khas pagination tailwind
            if (link && link.closest('nav')) {
                const url = link.getAttribute('href');
                
                // Pastikan ada URL valid
                if (url && url !== '#') {
                    e.preventDefault();   // Mencegah reload halaman
                    e.stopPropagation();  // Mencegah event "naik" ke sidebar/layout
                    
                    // Tambahkan query search saat ini ke URL pagination jika belum ada
                    const currentSearch = searchInput.value;
                    let finalUrl = url;
                    
                    if(currentSearch && !url.includes('search=')) {
                         finalUrl += (url.includes('?') ? '&' : '?') + `search=${encodeURIComponent(currentSearch)}`;
                    }

                    fetchEmployees(finalUrl);
                }
            }
        });
    });
</script>
@endsection