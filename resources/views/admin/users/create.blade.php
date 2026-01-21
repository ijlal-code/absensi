@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah User Baru</h2>
        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        {{-- Input Nama --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Lengkap (Username)</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Masukkan nama untuk login" required>
        </div>

        {{-- Input Password --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Buat password login" required>
        </div>

        {{-- Pilihan Hak Akses (Permissions) --}}
        <div class="mb-6 bg-gray-50 p-4 rounded border">
            <div class="flex justify-between items-center mb-3">
                <label class="block text-gray-700 font-bold">Hak Akses (Permissions)</label>
                
                {{-- FITUR BARU: Checkbox Pilih Semua --}}
                <label class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" id="selectAll" class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="font-semibold">Pilih Semua</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="create_events" class="permission-item rounded text-blue-600 focus:ring-blue-500">
                    <span>Bisa Buat Agenda Sendiri</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="view_employees" class="permission-item rounded text-blue-600 focus:ring-blue-500">
                    <span>Lihat Informasi Karyawan</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="view_statistics" class="permission-item rounded text-blue-600 focus:ring-blue-500">
                    <span>Lihat Statistik</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="manage_employees" class="permission-item rounded text-blue-600 focus:ring-blue-500">
                    <span>Kelola Data Karyawan (CRUD)</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" value="view_reports" class="permission-item rounded text-blue-600 focus:ring-blue-500">
                    <span>Lihat Laporan Absensi</span>
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-2">*Admin otomatis memiliki semua akses ini.</p>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800 transition">
            Simpan User
        </button>
    </form>
</div>

{{-- SCRIPT KHUSUS UNTUK PILIH SEMUA --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const permissionCheckboxes = document.querySelectorAll('.permission-item');

        // 1. Saat "Pilih Semua" diklik
        selectAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // 2. (Opsional) Jika user manual mencentang satu per satu sampai penuh, 
        // otomatis centang "Pilih Semua". Jika ada satu yang dilepas, lepas "Pilih Semua".
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    });
</script>
@endsection