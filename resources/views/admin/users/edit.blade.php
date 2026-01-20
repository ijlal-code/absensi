@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Atur Hak Akses: {{ $user->name }}</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Nama User</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border p-2 rounded bg-gray-50">
        </div>

        <div class="mb-6 p-4 border border-blue-200 rounded bg-blue-50">
            <div class="flex justify-between items-center border-b border-blue-200 pb-2 mb-4">
                <label class="block text-blue-900 font-bold text-lg">
                    Pilih Hak Akses
                </label>
                
                {{-- UPDATE DISINI: Checkbox Pilih Semua --}}
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-blue-900 rounded border-gray-300 focus:ring-blue-900">
                    <span class="ml-2 font-semibold text-blue-900 text-sm">Pilih / Batalkan Semua</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- KHUSUS: IZIN MEMBUAT AGENDA SENDIRI --}}
                <div class="col-span-2 bg-white p-3 rounded border border-gray-200 mb-2">
                    <label class="inline-flex items-center w-full cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="create_events" 
                        class="permission-checkbox form-checkbox h-5 w-5 text-green-600"
                        {{ in_array('create_events', $user->permissions ?? []) ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block font-bold text-gray-800">Dapat Membuat Agenda Sendiri</span>
                            <span class="text-sm text-gray-500">User bisa mengakses menu "Buat Agenda" dan mengelola agendanya sendiri.</span>
                        </div>
                    </label>
                </div>

                <label class="inline-flex items-center bg-white p-3 rounded border">
                    <input type="checkbox" name="permissions[]" value="view_employees" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_employees', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Lihat Informasi Karyawan</span>
                </label>

                <label class="inline-flex items-center bg-white p-3 rounded border">
                    <input type="checkbox" name="permissions[]" value="view_statistics" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_statistics', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Lihat Statistik Karyawan</span>
                </label>

                <label class="inline-flex items-center bg-white p-3 rounded border">
                    <input type="checkbox" name="permissions[]" value="manage_employees" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('manage_employees', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Kelola Data Karyawan (CRUD)</span>
                </label>

                <label class="inline-flex items-center bg-white p-3 rounded border">
                    <input type="checkbox" name="permissions[]" value="view_reports" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_reports', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Akses Laporan</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-800 font-bold">Simpan Hak Akses</button>
        </div>
    </form>
</div>

{{-- UPDATE DISINI: Script Javascript untuk Select All --}}
<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(chk => {
            chk.checked = e.target.checked;
        });
    });

    // Opsional: Cek otomatis "Pilih Semua" jika semua item sudah dicentang manual saat halaman dimuat
    window.addEventListener('load', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const selectAll = document.getElementById('selectAll');
        const allChecked = Array.from(checkboxes).every(c => c.checked);
        if(allChecked && checkboxes.length > 0) {
            selectAll.checked = true;
        }
    });
</script>

@endsection