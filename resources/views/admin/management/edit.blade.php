@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg p-8">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Karyawan: <span class="text-blue-600">{{ $employee->nama }}</span></h2>
            <a href="{{ route('employee-management.index') }}" class="text-gray-600 hover:text-gray-900 font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <form action="{{ route('employee-management.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- BAGIAN 1: IDENTITAS & KONTAK --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">1. Identitas & Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">SAP ID</label>
                        <input type="text" name="sap_id" value="{{ old('sap_id', $employee->sap_id) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $employee->nama) }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No HP 1 (Utama)</label>
                        <input type="text" name="no_hp_1" value="{{ old('no_hp_1', $employee->no_hp_1) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No HP 2</label>
                            <input type="text" name="no_hp_2" value="{{ old('no_hp_2', $employee->no_hp_2) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No HP 3</label>
                            <input type="text" name="no_hp_3" value="{{ old('no_hp_3', $employee->no_hp_3) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: POSISI & ORGANISASI --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">2. Posisi & Organisasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jabatan (Teks)</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Position Code</label>
                        <input type="text" name="position_code" value="{{ old('position_code', $employee->position_code) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                     <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Band</label>
                        <input type="text" name="band" value="{{ old('band', $employee->band) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unit Kerja (Biro)</label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja', $employee->unit_kerja) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seksi</label>
                        <input type="text" name="seksi" value="{{ old('seksi', $employee->seksi) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Departemen</label>
                        <input type="text" name="departemen" value="{{ old('departemen', $employee->departemen) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Direktorat</label>
                        <input type="text" name="direktorat" value="{{ old('direktorat', $employee->direktorat) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Cost Center</label>
                        <input type="text" name="cost_ctr" value="{{ old('cost_ctr', $employee->cost_ctr) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Subgroup</label>
                        <input type="text" name="subgroup" value="{{ old('subgroup', $employee->subgroup) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                </div>
            </div>

            {{-- BAGIAN 3: DATA PRIBADI --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">3. Data Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $employee->tempat_lahir) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $employee->tanggal_lahir ? $employee->tanggal_lahir->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Umur</label>
                        <input type="text" name="umur" value="{{ old('umur', $employee->umur) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" {{ $employee->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $employee->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Agama</label>
                        <input type="text" name="agama" value="{{ old('agama', $employee->agama) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pendidikan</label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan', $employee->pendidikan) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">{{ old('alamat', $employee->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 4: KEPEGAWAIAN --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">4. Kepegawaian</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $employee->tanggal_masuk ? $employee->tanggal_masuk->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Pensiun</label>
                        <input type="date" name="tanggal_pensiun" value="{{ old('tanggal_pensiun', $employee->tanggal_pensiun ? $employee->tanggal_pensiun->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Masa Kerja</label>
                        <input type="text" name="masa_kerja" value="{{ old('masa_kerja', $employee->masa_kerja) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>
                </div>
            </div>

            {{-- BAGIAN 5: DATA TEKNIS (LANJUTAN) --}}
            <div class="mb-8">
                <details class="group bg-gray-50 rounded-lg p-2 border">
                    <summary class="flex justify-between items-center font-medium cursor-pointer list-none text-blue-900 text-sm font-bold">
                        <span> 5. Data Teknis / SAP (Klik untuk membuka)</span>
                        <span class="transition group-open:rotate-180">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="text-gray-500 mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Personnel Area</label>
                            <input type="text" name="personnel_area" value="{{ old('personnel_area', $employee->personnel_area) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Abrev Position</label>
                            <input type="text" name="abrev_position" value="{{ old('abrev_position', $employee->abrev_position) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Abrev Organization</label>
                            <input type="text" name="abrev_organization" value="{{ old('abrev_organization', $employee->abrev_organization) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Dept</label>
                            <input type="text" name="obj_dept" value="{{ old('obj_dept', $employee->obj_dept) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Biro</label>
                            <input type="text" name="obj_biro" value="{{ old('obj_biro', $employee->obj_biro) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                         <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Sect</label>
                            <input type="text" name="obj_sect" value="{{ old('obj_sect', $employee->obj_sect) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                         <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Grp</label>
                            <input type="text" name="obj_grp" value="{{ old('obj_grp', $employee->obj_grp) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Organizational Unit</label>
                            <input type="text" name="organizational_unit" value="{{ old('organizational_unit', $employee->organizational_unit) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Cost Center Text</label>
                            <input type="text" name="cost_center_text" value="{{ old('cost_center_text', $employee->cost_center_text) }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                    </div>
                </details>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t mt-6 sticky bottom-0 bg-white p-4 shadow-inner">
                <a href="{{ route('employee-management.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md">
                    <i class="fas fa-save mr-1"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection