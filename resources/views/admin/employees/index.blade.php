@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: { 50:'#fef2f2', 100:'#fee2e2', 500:'#ef4444', 600:'#dc2626', 700:'#b91c1c' }
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">Informasi Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500">Database lengkap pegawai aktif.</p>
            </div>
            <div class="mt-4 flex gap-2 md:mt-0">
                <form action="{{ route('employees.index') }}" method="GET" class="flex w-full max-w-md gap-2">
                    <div class="relative rounded-md shadow-sm flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-600 sm:text-sm" 
                            placeholder="Cari NIK atau Nama...">
                    </div>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">Cari</button>
                    <a href="{{ route('employees.index') }}" class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-50" title="Refresh">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border-t-4 border-primary-600">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Aksi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Nama Pegawai</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Jabatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">Unit Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-primary-700 uppercase">No. HP</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $emp)
                        <tr class="hover:bg-red-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="openModal('modal-{{ $emp->id }}')" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold py-1.5 px-4 rounded-full shadow transition transform hover:scale-105">
                                    Detail
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $emp->nik }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border border-primary-200 mr-3">
                                        {{ substr($emp->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $emp->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->tkt_jabatan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->unit_kerja }}</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ $emp->no_hp_1 }}</td>
                        </tr>

                        <div id="modal-{{ $emp->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('modal-{{ $emp->id }}')"></div>

                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border-t-8 border-primary-600">
                                    
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b">
                                        <div class="sm:flex sm:items-start justify-between">
                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                <h3 class="text-2xl font-bold leading-6 text-gray-900" id="modal-title">Detil Karyawan</h3>
                                            </div>
                                            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal('modal-{{ $emp->id }}')">
                                                <span class="sr-only">Close</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="px-6 py-6 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                            
                                            <div class="md:col-span-3 flex flex-col gap-4">
                                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="text-center mb-2">
                                                        <span class="text-xs font-bold bg-primary-100 text-primary-700 px-2 py-1 rounded-full uppercase">Foto Sekarang</span>
                                                    </div>
                                                    <div class="aspect-[3/4] w-full bg-gray-200 rounded-md overflow-hidden flex items-center justify-center">
                                                        @if($emp->foto_sekarang)
                                                            <img src="{{ asset('storage/'.$emp->foto_sekarang) }}" class="object-cover w-full h-full" alt="Foto Sekarang">
                                                        @else
                                                            <div class="text-gray-400 text-xs text-center p-2">Tidak ada foto</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="text-center mb-2">
                                                        <span class="text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded-full uppercase">Foto Lama</span>
                                                    </div>
                                                    <div class="aspect-[3/4] w-full bg-gray-200 rounded-md overflow-hidden flex items-center justify-center opacity-80">
                                                        @if($emp->foto_lama)
                                                            <img src="{{ asset('storage/'.$emp->foto_lama) }}" class="object-cover w-full h-full" alt="Foto Lama">
                                                        @else
                                                            <div class="text-gray-400 text-xs text-center p-2">Tidak ada foto</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-9">
                                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                                                    <div class="px-6 py-4 border-b bg-gray-50">
                                                        <h4 class="text-lg font-bold text-gray-900">
                                                            Detail Informasi Karyawan
                                                        </h4>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Data personal dan kepegawaian
                                                        </p>
                                                    </div>

                                                    <div class="divide-y text-sm">

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Nama Karyawan</span>
                                                            <span class="col-span-2 font-bold text-gray-900">
                                                                {{ $emp->name }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">No Karyawan</span>
                                                            <span class="col-span-2">{{ $emp->nik }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Jabatan</span>
                                                            <span class="col-span-2">{{ $emp->tkt_jabatan }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Unit Kerja</span>
                                                            <span class="col-span-2">{{ $emp->unit_kerja }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Band</span>
                                                            <span class="col-span-2">{{ $emp->band ?? '-' }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Email</span>
                                                            <span class="col-span-2">{{ $emp->email ?? '-' }}</span>
                                                        </div>


                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Tanggal Lahir</span>
                                                            <span class="col-span-2 text-gray-900">
                                                                {{ $emp->tanggal_lahir ? date('d M Y', strtotime($emp->tanggal_lahir)) : '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Kewarganegaraan</span>
                                                            <span class="col-span-2">{{ $emp->kewarganegaraan ?? '-' }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Tanggal Masuk</span>
                                                            <span class="col-span-2">
                                                                {{ $emp->tanggal_masuk ? date('d M Y', strtotime($emp->tanggal_masuk)) : '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Jenis Kelamin</span>
                                                            <span class="col-span-2">{{ $emp->jenis_kelamin }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Agama</span>
                                                            <span class="col-span-2">{{ $emp->agama }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Status Perkawinan</span>
                                                            <span class="col-span-2">{{ $emp->status_perkawinan }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Alamat</span>
                                                            <span class="col-span-2">{{ $emp->alamat ?? '-' }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">Tanggal Pensiun</span>
                                                            <span class="col-span-2">
                                                                {{ $emp->tanggal_pensiun ? date('d M Y', strtotime($emp->tanggal_pensiun)) : '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">Lokasi</span>
                                                            <span class="col-span-2">{{ $emp->lokasi_kerja ?? '-' }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">No Ext</span>
                                                            <span class="col-span-2">{{ $emp->no_ext ?? '-' }}</span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">No Telpon 1 (Utama)</span>
                                                            <span class="col-span-2 text-green-600 font-medium">
                                                                {{ $emp->no_hp_1 ?? '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3 bg-gray-50">
                                                            <span class="font-medium text-gray-600">No Telpon 2</span>
                                                            <span class="col-span-2 text-gray-900">
                                                                {{ $emp->no_hp_2 ?? '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-3 px-6 py-3">
                                                            <span class="font-medium text-gray-600">No Telpon 3</span>
                                                            <span class="col-span-2 text-gray-900">
                                                                {{ $emp->no_hp_3 ?? '-' }}
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t">
                                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeModal('modal-{{ $emp->id }}')">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    }
</script>
@endsection