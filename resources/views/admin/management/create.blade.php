@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg p-8">
        {{-- Header Form --}}
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Data Karyawan</h2>
            <a href="{{ route('employee-management.index') }}" class="text-gray-600 hover:text-gray-900 font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Pesan Error Global --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <span class="block sm:inline">Mohon periksa kembali inputan yang bertanda merah.</span>
            </div>
        @endif

        {{-- Form Create --}}
        <form action="{{ route('employee-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- BAGIAN 1: IDENTITAS & KONTAK --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">1. Identitas & Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- NIK --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border @error('nik') border-red-500 @enderror">
                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- SAP ID --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">SAP ID <span class="text-red-500">*</span></label>
                        <input type="text" name="sap_id" value="{{ old('sap_id') }}" required 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border @error('sap_id') border-red-500 @enderror">
                        @error('sap_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border @error('nama') border-red-500 @enderror">
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- No HP 1 (MODIFIKASI) --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase">No HP 1</label>
                            <label class="flex items-center text-[10px] text-blue-700 cursor-pointer bg-blue-50 px-2 py-0.5 rounded border border-blue-100 hover:bg-blue-100 transition">
                                <input type="radio" name="primary_phone" value="no_hp_1" class="mr-1 accent-blue-600" checked> Set Utama
                            </label>
                        </div>
                        <input type="text" name="no_hp_1" value="{{ old('no_hp_1') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border">
                    </div>

                    {{-- No HP 2 & 3 (MODIFIKASI) --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase">No HP 2</label>
                                <label class="flex items-center text-[10px] text-blue-700 cursor-pointer bg-blue-50 px-1 py-0.5 rounded border border-blue-100 hover:bg-blue-100 transition">
                                    <input type="radio" name="primary_phone" value="no_hp_2" class="mr-1 accent-blue-600" {{ old('primary_phone') == 'no_hp_2' ? 'checked' : '' }}> Utama
                                </label>
                            </div>
                            <input type="text" name="no_hp_2" value="{{ old('no_hp_2') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase">No HP 3</label>
                                <label class="flex items-center text-[10px] text-blue-700 cursor-pointer bg-blue-50 px-1 py-0.5 rounded border border-blue-100 hover:bg-blue-100 transition">
                                    <input type="radio" name="primary_phone" value="no_hp_3" class="mr-1 accent-blue-600" {{ old('primary_phone') == 'no_hp_3' ? 'checked' : '' }}> Utama
                                </label>
                            </div>
                            <input type="text" name="no_hp_3" value="{{ old('no_hp_3') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
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
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Position Code</label>
                        <input type="text" name="position_code" value="{{ old('position_code') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                      <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Band</label>
                        <input type="text" name="band" value="{{ old('band') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unit Kerja (Biro)</label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seksi</label>
                        <input type="text" name="seksi" value="{{ old('seksi') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Departemen</label>
                        <input type="text" name="departemen" value="{{ old('departemen') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Direktorat</label>
                        <input type="text" name="direktorat" value="{{ old('direktorat') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Cost Center</label>
                        <input type="text" name="cost_ctr" value="{{ old('cost_ctr') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Subgroup</label>
                        <input type="text" name="subgroup" value="{{ old('subgroup') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                </div>
            </div>

            {{-- BAGIAN 3: DATA PRIBADI --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">3. Data Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required 
                               class="w-full border-gray-300 rounded-md text-sm p-2 border @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <small class="text-gray-500 italic">Umur akan dihitung otomatis.</small>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold uppercase mb-1" for="jenis_kelamin">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-- Pilih Gender --</option>
                            <option value="Male" {{ old('jenis_kelamin') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('jenis_kelamin') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Agama</label>
                        <input type="text" name="agama" value="{{ old('agama') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pendidikan</label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full border-gray-300 rounded-md text-sm p-2 border">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 4: KEPEGAWAIAN --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">4. Kepegawaian</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required 
                               class="w-full border-gray-300 rounded-md text-sm p-2 border @error('tanggal_masuk') border-red-500 @enderror">
                        @error('tanggal_masuk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <small class="text-gray-500 italic">Masa Kerja akan dihitung otomatis.</small>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Date Terminasi</label>
                        <input type="date" name="date_terminasi" value="{{ old('date_terminasi') }}" class="w-full border-gray-300 rounded-md text-sm p-2 border">
                    </div>
                </div>
            </div>

            {{-- BAGIAN 5: UPLOAD FOTO (BARU) --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-900 border-l-4 border-blue-600 pl-3 mb-4 bg-gray-50 p-2">5. Upload Foto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Terbaru</label>
                        <input type="file" name="foto_terbaru" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border rounded cursor-pointer @error('foto_terbaru') border-red-500 @enderror">
                        @error('foto_terbaru') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <small class="text-gray-400 block mt-1">Format: JPG/PNG, Max: 2MB</small>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Badge (Opsional)</label>
                        <input type="file" name="foto_lama" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 border rounded cursor-pointer @error('foto_lama') border-red-500 @enderror">
                        @error('foto_lama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- BAGIAN 6: DATA TEKNIS (LANJUTAN) --}}
            <div class="mb-8">
                <details class="group bg-gray-50 rounded-lg p-2 border">
                    <summary class="flex justify-between items-center font-medium cursor-pointer list-none text-blue-900 text-sm font-bold">
                        <span> 6. Data Teknis / SAP (Klik untuk membuka)</span>
                        <span class="transition group-open:rotate-180">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="text-gray-500 mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Personnel Area</label>
                            <input type="text" name="personnel_area" value="{{ old('personnel_area') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Abrev Position</label>
                            <input type="text" name="abrev_position" value="{{ old('abrev_position') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Abrev Organization</label>
                            <input type="text" name="abrev_organization" value="{{ old('abrev_organization') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Dept</label>
                            <input type="text" name="obj_dept" value="{{ old('obj_dept') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Biro</label>
                            <input type="text" name="obj_biro" value="{{ old('obj_biro') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                          <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Sect</label>
                            <input type="text" name="obj_sect" value="{{ old('obj_sect') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                          <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Obj Grp</label>
                            <input type="text" name="obj_grp" value="{{ old('obj_grp') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Organizational Unit</label>
                            <input type="text" name="organizational_unit" value="{{ old('organizational_unit') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Cost Center Text</label>
                            <input type="text" name="cost_center_text" value="{{ old('cost_center_text') }}" class="w-full border-gray-200 rounded text-xs p-1">
                        </div>
                    </div>
                </details>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-6 border-t mt-6 sticky bottom-0 bg-white p-4 shadow-inner">
                <a href="{{ route('employee-management.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection