@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#fef2f2',
                        100: '#fee2e2',
                        500: '#ef4444', // Merah
                        600: '#dc2626',
                        700: '#b91c1c',
                    }
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Informasi Data Karyawan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Database pegawai aktif (Terpisah dari User Login).
                </p>
            </div>
            
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <form action="{{ route('employees.index') }}" method="GET" class="flex w-full max-w-md gap-2">
                    
                    <div class="relative rounded-md shadow-sm flex-grow">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" 
                            placeholder="Cari NIK, Nama, Unit...">
                    </div>

                    <a href="{{ route('employees.index') }}" 
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                       title="Refresh / Reset Pencarian">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span class="ml-2 hidden sm:inline">Refresh</span>
                    </a>

                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">Nama Pegawai</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">Tkt. Jabatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">Unit Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase tracking-wider">No. Handphone</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $emp)
                        <tr class="hover:bg-red-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold py-1 px-3 rounded shadow">
                                    Detail
                                </button>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $emp->nik }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border border-primary-200 mr-3">
                                        {{ substr($emp->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $emp->name }}</div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $emp->tkt_jabatan }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 py-1 bg-gray-100 rounded-md text-gray-700 text-xs font-medium border border-gray-200">
                                    {{ $emp->unit_kerja }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center text-green-600 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                                    {{ $emp->no_hp_1 }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <span class="font-medium">Tidak ditemukan hasil untuk "{{ request('search') }}"</span>
                                    <a href="{{ route('employees.index') }}" class="mt-2 text-primary-600 hover:text-primary-700 text-sm font-semibold hover:underline">
                                        Refresh Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection