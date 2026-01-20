@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Penyelenggara</h2>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-user-plus"></i> Tambah User
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($users as $user)
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="font-bold text-lg text-gray-800">{{ $user->name }}</h3>
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Penyelenggara</span>
        </div>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="..." title="Hapus">
        </button>
</form>
    </div>
    @endforeach
</div>
@endsection