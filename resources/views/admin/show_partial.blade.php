<div class="flex flex-col md:flex-row gap-6">
    
    {{-- BAGIAN KIRI: FOTO & NAMA --}}
    <div class="w-full md:w-1/3 flex flex-col items-center border-r md:pr-4">
        @if($employee->foto_terbaru)
            <img src="{{ asset('storage/' . $employee->foto_terbaru) }}" class="w-32 h-32 rounded-full object-cover border-4 border-blue-50 shadow-lg mb-4 hover:scale-105 transition transform duration-300">
        @else
            <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 text-4xl font-bold mb-4 shadow-inner">
                {{ substr($employee->nama, 0, 1) }}
            </div>
        @endif

        <h3 class="text-xl font-bold text-gray-800 text-center leading-tight">{{ $employee->nama }}</h3>
        <p class="text-sm text-gray-500 font-mono mt-1 bg-gray-100 px-2 py-1 rounded">{{ $employee->nik }}</p>
        
        <div class="mt-4 w-full">
            <div class="text-xs text-center text-gray-400 uppercase tracking-wide mb-1">Status</div>
            <div class="text-center">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: DETAIL INFORMASI --}}
    <div class="w-full md:w-2/3 space-y-5">
        
        {{-- INFORMASI KONTAK (DENGAN BADGE UTAMA) --}}
        <div>
            <h4 class="font-bold text-gray-700 border-b pb-2 mb-3 flex items-center">
                <i class="fas fa-address-book text-blue-500 mr-2"></i> Kontak & HP
            </h4>
            
            <div class="space-y-2">
                {{-- HP 1 --}}
                <div class="flex justify-between items-center p-3 rounded-lg {{ ($employee->primary_phone ?? 'no_hp_1') == 'no_hp_1' ? 'bg-blue-50 border border-blue-200 shadow-sm' : 'bg-gray-50 border border-transparent' }}">
                    <div class="flex items-center">
                        <i class="fas fa-phone-alt {{ ($employee->primary_phone ?? 'no_hp_1') == 'no_hp_1' ? 'text-blue-500' : 'text-gray-400' }} mr-3 text-sm"></i>
                        <span class="text-sm text-gray-600">HP 1: <span class="font-semibold text-gray-800 ml-1">{{ $employee->no_hp_1 ?? '-' }}</span></span>
                    </div>
                    @if(($employee->primary_phone ?? 'no_hp_1') == 'no_hp_1')
                        <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full uppercase font-bold tracking-wider shadow-sm">
                            <i class="fas fa-check mr-1"></i>Utama
                        </span>
                    @endif
                </div>

                {{-- HP 2 --}}
                <div class="flex justify-between items-center p-3 rounded-lg {{ $employee->primary_phone == 'no_hp_2' ? 'bg-blue-50 border border-blue-200 shadow-sm' : 'bg-gray-50 border border-transparent' }}">
                    <div class="flex items-center">
                        <i class="fas fa-phone-alt {{ $employee->primary_phone == 'no_hp_2' ? 'text-blue-500' : 'text-gray-400' }} mr-3 text-sm"></i>
                        <span class="text-sm text-gray-600">HP 2: <span class="font-semibold text-gray-800 ml-1">{{ $employee->no_hp_2 ?? '-' }}</span></span>
                    </div>
                    @if($employee->primary_phone == 'no_hp_2')
                        <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full uppercase font-bold tracking-wider shadow-sm">
                            <i class="fas fa-check mr-1"></i>Utama
                        </span>
                    @endif
                </div>

                {{-- HP 3 --}}
                <div class="flex justify-between items-center p-3 rounded-lg {{ $employee->primary_phone == 'no_hp_3' ? 'bg-blue-50 border border-blue-200 shadow-sm' : 'bg-gray-50 border border-transparent' }}">
                    <div class="flex items-center">
                        <i class="fas fa-phone-alt {{ $employee->primary_phone == 'no_hp_3' ? 'text-blue-500' : 'text-gray-400' }} mr-3 text-sm"></i>
                        <span class="text-sm text-gray-600">HP 3: <span class="font-semibold text-gray-800 ml-1">{{ $employee->no_hp_3 ?? '-' }}</span></span>
                    </div>
                    @if($employee->primary_phone == 'no_hp_3')
                        <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full uppercase font-bold tracking-wider shadow-sm">
                            <i class="fas fa-check mr-1"></i>Utama
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- INFORMASI PEKERJAAN --}}
        <div>
            <h4 class="font-bold text-gray-700 border-b pb-2 mb-3 flex items-center">
                <i class="fas fa-briefcase text-blue-500 mr-2"></i> Pekerjaan
            </h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="block text-xs text-gray-500">Jabatan</span>
                    <span class="font-semibold text-gray-800">{{ $employee->jabatan ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Unit Kerja</span>
                    <span class="font-semibold text-gray-800">{{ $employee->unit_kerja ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Departemen</span>
                    <span class="font-semibold text-gray-800">{{ $employee->departemen ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">SAP ID</span>
                    <span class="font-semibold text-gray-800">{{ $employee->sap_id ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Masa Kerja</span>
                    <span class="font-semibold text-gray-800">{{ $employee->masa_kerja }} Tahun</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Tanggal Masuk</span>
                    <span class="font-semibold text-gray-800">{{ $employee->tanggal_masuk ? $employee->tanggal_masuk->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>