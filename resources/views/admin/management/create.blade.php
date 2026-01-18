@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Data Karyawan</h2>
            <a href="{{ route('employee-management.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        {{-- ERROR HANDLING --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- FORM START --}}
        <form action="{{ route('employee-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. INFORMASI DASAR --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIK *</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">SAP ID *</label>
                        <input type="text" name="sap_id" value="{{ old('sap_id') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih --</option>
                            <option value="Male" {{ old('jenis_kelamin') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('jenis_kelamin') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Agama</label>
                        <input type="text" name="agama" value="{{ old('agama') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- 2. KONTAK & HP (BAGIAN UTAMA PERUBAHAN) --}}
            <div class="mb-8 bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 border-b border-blue-200 pb-2 mb-4">
                    <i class="fas fa-phone-alt mr-2"></i>Kontak & Nomor HP
                </h3>
                <p class="text-sm text-gray-600 mb-4">Masukkan nomor HP dan <strong>pilih salah satu</strong> (klik tombol "Set Utama") sebagai nomor yang akan ditampilkan di dashboard.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- No HP 1 --}}
                    <div class="relative">
                        <label class="block text-gray-700 text-sm font-bold mb-2 flex justify-between items-center">
                            <span>No HP 1</span>
                            <div class="flex items-center text-xs text-blue-600 cursor-pointer hover:text-blue-800">
                                <input type="radio" name="primary_phone" value="no_hp_1" class="mr-1 accent-blue-600 cursor-pointer" checked> 
                                <span>Set Utama</span>
                            </div>
                        </label>
                        <input type="text" name="no_hp_1" value="{{ old('no_hp_1') }}" placeholder="0812..." class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- No HP 2 --}}
                    <div class="relative">
                        <label class="block text-gray-700 text-sm font-bold mb-2 flex justify-between items-center">
                            <span>No HP 2</span>
                            <div class="flex items-center text-xs text-blue-600 cursor-pointer hover:text-blue-800">
                                <input type="radio" name="primary_phone" value="no_hp_2" class="mr-1 accent-blue-600 cursor-pointer" {{ old('primary_phone') == 'no_hp_2' ? 'checked' : '' }}> 
                                <span>Set Utama</span>
                            </div>
                        </label>
                        <input type="text" name="no_hp_2" value="{{ old('no_hp_2') }}" placeholder="0812..." class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- No HP 3 --}}
                    <div class="relative">
                        <label class="block text-gray-700 text-sm font-bold mb-2 flex justify-between items-center">
                            <span>No HP 3</span>
                            <div class="flex items-center text-xs text-blue-600 cursor-pointer hover:text-blue-800">
                                <input type="radio" name="primary_phone" value="no_hp_3" class="mr-1 accent-blue-600 cursor-pointer" {{ old('primary_phone') == 'no_hp_3' ? 'checked' : '' }}> 
                                <span>Set Utama</span>
                            </div>
                        </label>
                        <input type="text" name="no_hp_3" value="{{ old('no_hp_3') }}" placeholder="0812..." class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- 3. DETAIL PEKERJAAN --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Detail Pekerjaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk *</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unit Kerja</label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Departemen</label>
                        <input type="text" name="departemen" value="{{ old('departemen') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- 4. UPLOAD FOTO --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Upload Foto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Foto Terbaru --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition">
                        <i class="fas fa-camera text-3xl text-gray-400 mb-3"></i>
                        <h4 class="font-bold text-gray-700 mb-1">Foto Terbaru</h4>
                        <p class="text-xs text-gray-500 mb-3">Format: JPG, PNG (Max 2MB)</p>
                        <input type="file" name="foto_terbaru" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    {{-- Foto Lama --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition">
                        <i class="fas fa-history text-3xl text-gray-400 mb-3"></i>
                        <h4 class="font-bold text-gray-700 mb-1">Foto Lama / Arsip</h4>
                        <p class="text-xs text-gray-500 mb-3">Format: JPG, PNG (Max 2MB)</p>
                        <input type="file" name="foto_lama" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>

                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex justify-end gap-4 mt-8">
                <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Reset</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md">
                    <i class="fas fa-save mr-2"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection