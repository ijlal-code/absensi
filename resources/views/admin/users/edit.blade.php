@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Hak Akses User: {{ $user->name }}</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Hak Akses (Permissions)</label>
            
            {{-- Checkbox Pilih Semua --}}
            <div class="mb-2 pb-2 border-b">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 font-bold text-blue-900">Beri Semua Akses</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Opsi 1 --}}
                <label class="inline-flex items-center">
                    <input type="checkbox" name="permissions[]" value="view_employees" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_employees', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Informasi Karyawan</span>
                </label>

                {{-- Opsi 2 --}}
                <label class="inline-flex items-center">
                    <input type="checkbox" name="permissions[]" value="view_statistics" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_statistics', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Statistik Karyawan</span>
                </label>

                {{-- Opsi 3 --}}
                <label class="inline-flex items-center">
                    <input type="checkbox" name="permissions[]" value="manage_employees" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('manage_employees', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Kelola Karyawan (CRUD)</span>
                </label>

                {{-- Opsi 4 --}}
                <label class="inline-flex items-center">
                    <input type="checkbox" name="permissions[]" value="view_reports" class="permission-checkbox form-checkbox h-5 w-5 text-blue-600"
                    {{ in_array('view_reports', $user->permissions ?? []) ? 'checked' : '' }}>
                    <span class="ml-2">Laporan</span>
                </label>
            </div>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Simpan Perubahan</button>
        <a href="{{ route('admin.users.index') }}" class="text-gray-600 ml-4">Batal</a>
    </form>
</div>

<script>
    // Script untuk Select All
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(chk => {
            chk.checked = e.target.checked;
        });
    });
</script>
@endsection