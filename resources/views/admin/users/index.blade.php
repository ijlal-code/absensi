@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Management User & Hak Akses</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6">Nama User</th>
                    <th class="py-3 px-6">Agenda Dibuat</th>
                    <th class="py-3 px-6">Hak Akses Aktif</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 font-medium">{{ $user->name }}</td>
                    
                    {{-- LIST AGENDA YANG DIBUAT USER --}}
                    <td class="py-3 px-6">
                        @if($user->events->count() > 0)
                            <ul class="list-disc list-inside text-xs">
                                @foreach($user->events->take(5) as $event)
                                    <li>{{ Str::limit($event->title, 30) }}</li>
                                @endforeach
                                @if($user->events->count() > 5)
                                    <li class="text-gray-400 font-italic">+ {{ $user->events->count() - 5 }} lainnya</li>
                                @endif
                            </ul>
                        @else
                            <span class="text-gray-400 italic">Belum membuat agenda</span>
                        @endif
                    </td>

                    {{-- LIST PERMISSION --}}
                    <td class="py-3 px-6">
                        <div class="flex flex-wrap gap-1">
                            @if($user->permissions)
                                @foreach($user->permissions as $perm)
                                    <span class="bg-blue-100 text-blue-800 py-1 px-2 rounded text-xs">
                                        {{ str_replace('_', ' ', $perm) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-red-500 text-xs">Tidak ada akses</span>
                            @endif
                        </div>
                    </td>

                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            {{-- TOMBOL KE HALAMAN EDIT CENTANG --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" title="Atur Hak Akses">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection