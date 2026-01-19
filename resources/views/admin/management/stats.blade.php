@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistik Karyawan</h2>
            <p class="text-gray-600 text-sm">Visualisasi data demografi karyawan Semen Tonasa.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: KONTROL GRAFIK --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-md p-5">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Kategori Data</h3>
                
                <div class="flex flex-col gap-3">
                    <button onclick="changeCategory('education')" id="btn-education" class="chart-btn active flex items-center p-3 rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-700 transition w-full">
                        <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Pendidikan</span>
                            <span class="text-xs text-gray-500">Jenjang Akademik</span>
                        </div>
                    </button>

                    <button onclick="changeCategory('service')" id="btn-service" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
                        <div class="bg-teal-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Masa Kerja</span>
                            <span class="text-xs text-gray-500">Lama Pengabdian</span>
                        </div>
                    </button>

                    <button onclick="changeCategory('age')" id="btn-age" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
                        <div class="bg-indigo-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-user-clock text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Usia</span>
                            <span class="text-xs text-gray-500">Kelompok Umur</span>
                        </div>
                    </button>

                    <button onclick="changeCategory('gender')" id="btn-gender" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
                        <div class="bg-gradient-to-br from-blue-500 to-pink-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-venus-mars text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Jenis Kelamin</span>
                            <span class="text-xs text-gray-500">Laki-laki & Perempuan</span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-xl shadow-md p-5 text-center transform transition hover:scale-105">
                <h4 class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Data Terdata</h4>
                <p id="total-display" class="text-4xl font-extrabold text-gray-800">{{ $educationData->sum() }}</p>
                <span class="text-xs text-green-500 font-semibold"><i class="fas fa-check-circle"></i> Data Terupdate</span>
            </div>
        </div>

        {{-- KOLOM KANAN: AREA GRAFIK --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 h-full min-h-[550px] flex flex-col relative">
                
                {{-- Header Grafik & Tombol Sort --}}
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h3 id="chart-title" class="text-xl font-bold text-gray-800">Persentase Tingkat Pendidikan</h3>
                    
                    {{-- TOMBOL SORTING --}}
                    <div class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-lg border border-gray-200">
                        <span class="text-xs font-semibold text-gray-500 ml-2 mr-1">Urutan:</span>
                        <button onclick="toggleSort('asc')" id="sort-asc" class="sort-btn px-3 py-1 rounded text-xs font-bold transition bg-white shadow text-blue-600 border border-gray-200">
                            <i class="fas fa-sort-amount-down-alt mr-1"></i> Kecil - Besar
                        </button>
                        <button onclick="toggleSort('desc')" id="sort-desc" class="sort-btn px-3 py-1 rounded text-xs font-bold transition text-gray-500 hover:bg-gray-100">
                            <i class="fas fa-sort-amount-up mr-1"></i> Besar - Kecil
                        </button>
                    </div>
                </div>
                
                {{-- Container Canvas Responsif --}}
                <div class="flex-grow relative w-full flex items-center justify-center">
                    <div id="echart-container" class="w-full h-[450px]"></div>
                </div>
                
                <div class="mt-2 text-center text-xs text-gray-400 border-t pt-2">
                    * Arahkan kursor pada grafik untuk melihat detail jumlah orang. Urutan searah jarum jam.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<script>
    // --- 1. Definisi Hierarchy (Urutan Logika untuk Sorting) ---
    const hierarchies = {
        education: ['Belum Diisi', 'SMA', 'SMA/Setingkat', 'SLTA', 'Diploma 3', 'Strata 1', 'Strata 2'],
        service: [
            '0 - 5 Tahun', '6 - 10 Tahun', '11 - 20 Tahun', '21 - 25 Tahun',
            '26 - 30 Tahun', '31 - 35 Tahun', '36 - 40 Tahun', '41 - 45 Tahun', 
            '46 - 50 Tahun', '> 50 Tahun'
        ],
        age: ['< 25 Tahun', '25 - 35 Tahun', '36 - 45 Tahun', '46 - 55 Tahun', '> 55 Tahun']
    };

    // --- 2. Definisi Warna Custom & Palet Dasar ---
    
    // Palet warna dasar untuk item yang tidak didefinisikan secara khusus
    const distinctColors = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
        '#EC4899', '#06B6D4', '#F97316', '#6366F1', '#84CC16', '#14B8A6'
    ];

    // Peta Warna Spesifik (Request User)
    const categoryColorMap = {
        // A. Kategori Pendidikan
        education: {
            'Belum Diisi': '#EF4444', // Merah (REQUESTED)
            'Strata 1': '#3B82F6',    // Biru (REQUESTED)
            
            // Sisanya kita atur manual agar kontras dan bagus
            'SMA': '#10B981',           // Hijau
            'SMA/Setingkat': '#10B981', // Hijau
            'Diploma 3': '#F59E0B',     // Kuning/Amber
            'Strata 2': '#8B5CF6'       // Ungu
        },

        // B. Kategori Masa Kerja
        service: {
            // Urutan warna asli (index): 0:Biru, 1:Hijau, 2:Kuning, 3:Merah, 4:Ungu, 5:Pink ...
            '0 - 5 Tahun': '#3B82F6',   // Biru
            '6 - 10 Tahun': '#34f1b2',  // Hijau
            '11 - 20 Tahun': '#faae2a', // Kuning
            '21 - 25 Tahun': '#7cf735', // Merah
            
            // TUKAR WARNA (REQUESTED): 
            // Aslinya urutan ke-5 (26-30) adalah Ungu (#8B5CF6) dan ke-6 (31-35) adalah Pink (#EC4899)
            // Kita tukar posisinya:
            '26 - 30 Tahun': '#d240f0', // Sekarang Pink
            '31 - 35 Tahun': '#f63333', // Sekarang Ungu
            
            '36 - 40 Tahun': '#39d5f1', // Cyan
            '41 - 45 Tahun': '#F97316', // Orange
            '46 - 50 Tahun': '#6366F1', // Indigo   
            '> 50 Tahun': '#84CC16'     // Lime
        },

        // C. Kategori Gender (Tetap)
        gender: {
            'Laki-laki': '#3B82F6', 'Pria': '#3B82F6', 'Male': '#3B82F6',
            'Perempuan': '#EC4899', 'Wanita': '#EC4899', 'Female': '#EC4899',
            'Tidak Diketahui': '#9CA3AF'
        },
        
        // D. Kategori Usia (Default mapping agar konsisten)
        age: {
            '< 25 Tahun': '#3B82F6',
            '25 - 35 Tahun': '#10B981',
            '36 - 45 Tahun': '#F59E0B',
            '46 - 55 Tahun': '#EF4444',
            '> 55 Tahun': '#8B5CF6'
        }
    };

    function formatData(keys, values) {
        return keys.map((key, index) => {
            return { value: values[index], name: key };
        });
    }

    // Mengambil data mentah dari PHP
    const rawData = {
        education: {
            data: formatData({!! json_encode($educationData->keys()) !!}, {!! json_encode($educationData->values()) !!}),
            title: 'Persentase Tingkat Pendidikan'
        },
        service: {
            data: formatData({!! json_encode($serviceData->keys()) !!}, {!! json_encode($serviceData->values()) !!}),
            title: 'Persentase Masa Kerja'
        },
        age: {
            data: formatData({!! json_encode($ageData->keys()) !!}, {!! json_encode($ageData->values()) !!}),
            title: 'Persentase Tingkat Usia'
        },
        gender: {
            data: formatData({!! json_encode($genderData->keys()) !!}, {!! json_encode($genderData->values()) !!}),
            title: 'Persentase Jenis Kelamin'
        }
    };

    // --- 3. State Management ---
    let currentCategory = 'education';
    let currentSortDir = 'asc'; 
    let myChart = echarts.init(document.getElementById('echart-container'));

    window.addEventListener('resize', () => myChart.resize());

    // --- 4. Fungsi Sorting Custom ---
    function getSortedData(category, direction) {
        let items = [...rawData[category].data];
        
        items.sort((a, b) => {
            let valA, valB;

            // KASUS 1: Sorting berdasarkan Nilai (Khusus Gender)
            if (category === 'gender') {
                valA = a.value;
                valB = b.value;
            } 
            // KASUS 2: Sorting berdasarkan Hierarchy
            else if (hierarchies[category]) {
                valA = hierarchies[category].indexOf(a.name);
                valB = hierarchies[category].indexOf(b.name);
                if (valA === -1) valA = 999;
                if (valB === -1) valB = 999;
            }
            // Fallback
            else {
                valA = a.name;
                valB = b.name;
            }

            if (valA < valB) return direction === 'asc' ? -1 : 1;
            if (valA > valB) return direction === 'asc' ? 1 : -1;
            return 0;
        });

        return items;
    }

    // --- 5. Helper: Get Color for Item ---
    function getColorForName(category, name, index) {
        // 1. Cek apakah ada mapping khusus (misal: "Belum Diisi" di Education)
        if (categoryColorMap[category] && categoryColorMap[category][name]) {
            return categoryColorMap[category][name];
        }
        // 2. Jika tidak ada, gunakan warna dari palet distinctColors secara berurutan
        // Gunakan modulus agar jika data banyak, warna berulang
        return distinctColors[index % distinctColors.length];
    }

    // --- 6. Render Chart ---
    function renderChart() {
        const dataset = rawData[currentCategory];
        
        // Update Judul & Total
        document.getElementById('chart-title').innerText = dataset.title;
        const total = dataset.data.reduce((acc, curr) => acc + curr.value, 0);
        document.getElementById('total-display').innerText = total;

        // Ambil Data yang Sudah Disortir
        let chartData = getSortedData(currentCategory, currentSortDir);

        // MAP WARNA KE DATA (Penting: Warna melekat pada Item, bukan posisi)
        chartData = chartData.map((item, index) => ({
            ...item,
            itemStyle: { 
                color: getColorForName(currentCategory, item.name, index)
            }
        }));

        const option = {
            tooltip: {
                trigger: 'item',
                formatter: '{b}: <br/><b>{c} Orang</b> ({d}%)',
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                textStyle: { color: '#1f2937' }
            },
            legend: {
                bottom: '0%',
                left: 'center',
                itemGap: 15,
                textStyle: { fontSize: 12, color: '#4B5563' }
            },
            series: [
                {
                    name: dataset.title,
                    type: 'pie',
                    radius: ['40%', '70%'],
                    center: ['50%', '45%'],
                    
                    sort: null, 
                    clockwise: true,

                    itemStyle: {
                        borderRadius: 5,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: true,
                        position: 'outside',
                        formatter: '{b}\n{d}%',
                        fontWeight: '600',
                        fontSize: 12,
                        color: '#374151'
                    },
                    labelLine: { show: true, length: 15, smooth: true },
                    
                    data: chartData,
                    
                    animationType: 'scale',
                    animationEasing: 'elasticOut'
                }
            ]
        };

        myChart.setOption(option, { notMerge: true });
    }

    // --- 7. Event Handlers ---
    window.changeCategory = function(type) {
        currentCategory = type;

        // Reset Style Tombol
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.className = 'chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full';
        });

        // Set Active Style
        const btn = document.getElementById('btn-' + type);
        btn.classList.remove('border-transparent', 'hover:bg-gray-50');
        
        if(type === 'education') btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-500');
        if(type === 'service') btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-500');
        if(type === 'age') btn.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-500');
        if(type === 'gender') btn.classList.add('bg-pink-50', 'text-pink-700', 'border-pink-500');

        currentSortDir = 'asc'; 
        updateSortButtonsUI();
        renderChart();
    }

    window.toggleSort = function(direction) {
        currentSortDir = direction;
        updateSortButtonsUI();
        renderChart();
    }

    function updateSortButtonsUI() {
        const btnAsc = document.getElementById('sort-asc');
        const btnDesc = document.getElementById('sort-desc');

        if (currentSortDir === 'asc') {
            btnAsc.className = "sort-btn px-3 py-1 rounded text-xs font-bold transition bg-white shadow text-blue-600 border border-gray-200";
            btnDesc.className = "sort-btn px-3 py-1 rounded text-xs font-bold transition text-gray-500 hover:bg-gray-100";
        } else {
            btnAsc.className = "sort-btn px-3 py-1 rounded text-xs font-bold transition text-gray-500 hover:bg-gray-100";
            btnDesc.className = "sort-btn px-3 py-1 rounded text-xs font-bold transition bg-white shadow text-blue-600 border border-gray-200";
        }
    }

    // --- 8. Init ---
    document.addEventListener('DOMContentLoaded', () => {
        changeCategory('education'); 
    });
</script>
@endsection