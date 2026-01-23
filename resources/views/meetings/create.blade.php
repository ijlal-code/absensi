@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Buat Minutes of Meeting Baru</h2>

        <form action="{{ route('meetings.store') }}" method="POST">
            @csrf
            
            {{-- Header Form --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Type of Meeting</label>
                        <input type="text" name="type_of_meeting" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Rapat Internal Dept Internal Audit">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitator</label>
                        {{-- HAPUS value="{{ Auth::user()->name }}" agar manual --}}
                        <input type="text" name="facilitator" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama Fasilitator">
                    </div>
                    
                    {{-- LOKASI (HANYA DROPDOWN BIASA) --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                        <select name="location" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>-- Pilih Lokasi --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        {{-- Link opsional jika ingin menambah lokasi baru --}}
                        <div class="mt-1 text-xs text-gray-500">
                            Lokasi tidak ada? <a href="{{ route('meetings.locations.index') }}" class="text-blue-600 hover:underline" target="_blank">Kelola Lokasi</a>
                        </div>
                    </div>
                    
                    {{-- NAMA NOTULIS (AUTOCOMPLETE BY SAP) --}}
                    <div x-data="employeeSearch()">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Notulis (Cari SAP/Nama)</label>
                        <div class="relative">
                            <input type="text" 
                                x-model="search" 
                                @input.debounce.500ms="fetchEmployees" 
                                @keydown.escape="isOpen = false"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Ketik SAP atau Nama..." 
                                autocomplete="off">
                            
                            {{-- Hidden input untuk nilai yang dikirim ke server --}}
                            <input type="hidden" name="notulis" x-model="selectedName">

                            {{-- Dropdown Suggestion --}}
                            <div x-show="isOpen && employees.length > 0" 
                                 @click.away="isOpen = false" 
                                 class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto"
                                 style="display: none;">
                                <ul>
                                    <template x-for="emp in employees" :key="emp.id">
                                        <li @click="selectEmployee(emp)" class="px-4 py-2 hover:bg-blue-100 cursor-pointer border-b last:border-b-0">
                                            <div class="font-bold text-gray-800" x-text="emp.nama"></div>
                                            <div class="text-xs text-gray-500">SAP: <span x-text="emp.sap_id"></span></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div x-show="isOpen && employees.length === 0 && search.length > 2" class="absolute z-10 w-full bg-white border p-2 text-sm text-gray-500 mt-1">
                                Tidak ditemukan.
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-show="selectedName">Terpilih: <span class="font-bold" x-text="selectedName"></span></p>
                    </div>

                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                        {{-- TETAP OTOMATIS --}}
                        <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                        {{-- TETAP OTOMATIS --}}
                        <input type="time" name="start_time" class="w-full border rounded px-3 py-2" value="{{ now('Asia/Makassar')->format('H:i') }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Presenter</label>
                            <input type="text" name="presenter" class="w-full border rounded px-3 py-2" placeholder="Ex: Administrator">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Durasi</label>
                            <input type="text" name="meeting_duration" class="w-full border rounded px-3 py-2" placeholder="Ex: 90 Menit">
                        </div>
                    </div>
                    <div>
                         <label class="block text-gray-700 text-sm font-bold mb-2">Daftar Hadir</label>
                         {{-- HAPUS value="Terlampir" --}}
                         <input type="text" name="attendees_list" class="w-full border rounded px-3 py-2" placeholder="Ex: Terlampir">
                    </div>
                </div>
            </div>

            {{-- Dynamic Action Items --}}
            <div class="mb-6" x-data="{ items: [{task: '', pic: '', deadline: ''}] }">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Action Items / Notulensi</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-4 mb-3 items-start">
                            <div class="w-10 pt-2 font-bold text-gray-500" x-text="index + 1 + '.'"></div>
                            <div class="flex-grow">
                                <textarea :name="'action_items['+index+'][task]'" x-model="item.task" class="w-full border rounded px-3 py-2" placeholder="Isi pembahasan..." rows="2"></textarea>
                            </div>
                            <div class="w-1/4">
                                <input type="text" :name="'action_items['+index+'][pic]'" x-model="item.pic" class="w-full border rounded px-3 py-2 mb-2" placeholder="PIC">
                                <input type="date" :name="'action_items['+index+'][deadline]'" x-model="item.deadline" class="w-full border rounded px-3 py-2 text-sm text-red-600">
                            </div>
                            <button type="button" @click="items.splice(index, 1)" class="text-red-500 hover:text-red-700 pt-2" x-show="items.length > 1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="items.push({task: '', pic: '', deadline: ''})" class="mt-2 text-sm bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('meetings.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-lg">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Logic untuk Autocomplete Karyawan
    function employeeSearch() {
        return {
            search: '',
            selectedName: '', 
            employees: [],
            isOpen: false,
            fetchEmployees() {
                if (this.search.length < 2) {
                    this.employees = [];
                    this.isOpen = false;
                    return;
                }
                
                fetch(`{{ route('meetings.search.employee') }}?query=${this.search}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        this.employees = data;
                        this.isOpen = data.length > 0;
                    })
                    .catch(error => {
                        console.error('Error fetching employees:', error);
                    });
            },
            selectEmployee(emp) {
                this.search = emp.nama; 
                this.selectedName = emp.nama; 
                this.employees = [];
                this.isOpen = false;
            }
        }
    }
</script>
@endsection