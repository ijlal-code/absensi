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
                        <input type="text" name="facilitator" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ Auth::user()->name }}">
                    </div>
                    
                    {{-- LOKASI (DYNAMIC DROPDOWN) --}}
                    <div x-data="locationManager()">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-gray-700 text-sm font-bold">Lokasi</label>
                            <button type="button" @click="openModal" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                <i class="fas fa-cog"></i> Kelola Lokasi
                            </button>
                        </div>
                        <select name="location" id="locationSelect" class="w-full border rounded px-3 py-2 bg-white">
                            <option value="" disabled selected>-- Pilih Lokasi --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>

                        {{-- Modal Kelola Lokasi --}}
                        <div x-show="isOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Manajemen Lokasi</h3>
                                    
                                    {{-- Input Tambah --}}
                                    <div class="mt-2 flex gap-2">
                                        <input type="text" x-model="newLocation" @keydown.enter.prevent="addLocation" placeholder="Nama Lokasi Baru" class="border rounded px-2 py-1 w-full text-sm">
                                        <button type="button" @click="addLocation" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Add</button>
                                    </div>

                                    {{-- List Lokasi --}}
                                    <div class="mt-4 text-left max-h-40 overflow-y-auto">
                                        <template x-for="loc in locationsList" :key="loc.id">
                                            <div class="flex justify-between items-center py-1 border-b text-sm">
                                                <span x-text="loc.name"></span>
                                                <button type="button" @click="deleteLocation(loc.id)" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="items-center px-4 py-3">
                                        <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
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
     style="display: none;"> {{-- Tambahkan style none agar tidak flicker saat load --}}
    <ul>
        <template x-for="emp in employees" :key="emp.id">
            <li @click="selectEmployee(emp)" class="px-4 py-2 hover:bg-blue-100 cursor-pointer border-b last:border-b-0">
                <div class="font-bold text-gray-800" x-text="emp.nama"></div>
                {{-- PERBAIKAN: Tampilkan sap_id --}}
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

                {{-- Kolom Kanan (Tidak berubah banyak) --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                        <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
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
                         <input type="text" name="attendees_list" class="w-full border rounded px-3 py-2" placeholder="Ex: Terlampir" value="Terlampir">
                    </div>
                </div>
            </div>

            {{-- Dynamic Action Items (Sama seperti sebelumnya) --}}
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

{{-- SCRIPT ALPINE JS --}}
<script>
    // Logic untuk Autocomplete Karyawan
    function employeeSearch() {
        return {
            search: '',
            selectedName: '', 
            employees: [],
            isOpen: false,
            fetchEmployees() {
                // Cari jika karakter lebih dari 2
                if (this.search.length < 2) {
                    this.employees = [];
                    this.isOpen = false;
                    return;
                }
                
                // Panggil API
                fetch(`{{ route('meetings.search.employee') }}?query=${this.search}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        this.employees = data;
                        // Buka dropdown hanya jika ada data
                        this.isOpen = data.length > 0;
                    })
                    .catch(error => {
                        console.error('Error fetching employees:', error);
                    });
            },
            selectEmployee(emp) {
                // PERBAIKAN: Set nama, dan gunakan sap_id untuk debug/log jika perlu
                this.search = emp.nama; 
                this.selectedName = emp.nama; 
                this.employees = [];
                this.isOpen = false;
            }
        }
    }

    // Logic untuk Manajemen Lokasi (Tidak berubah, tetap sertakan ini)
    function locationManager() {
        return {
            isOpen: false,
            newLocation: '',
            locationsList: @json($locations), 
            
            openModal() { this.isOpen = true; },
            closeModal() { this.isOpen = false; },
            
            addLocation() {
                if(!this.newLocation) return;
                
                fetch('{{ route("meetings.location.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: this.newLocation })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.locationsList.push(data.location);
                        let select = document.getElementById('locationSelect');
                        let option = new Option(data.location.name, data.location.name);
                        select.add(option);
                        select.value = data.location.name;
                        this.newLocation = '';
                    } else {
                        alert('Gagal menambah lokasi');
                    }
                });
            },
            
            deleteLocation(id) {
                if(!confirm('Hapus lokasi ini?')) return;

                fetch(`/rapat/location/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.locationsList = this.locationsList.filter(l => l.id !== id);
                    }
                });
            }
        }
    }
</script>
@endsection