@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistik Karyawan</h2>
            <p class="text-gray-600 text-sm">Visualisasi data demografi karyawan Semen Tonasa.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: KONTROL GRAFIK --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-md p-5">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Kategori Data</h3>
                
                <div class="flex flex-col gap-3">
                    <button onclick="updateChart('education')" id="btn-education" class="chart-btn active flex items-center p-3 rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-700 transition w-full">
                        <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Pendidikan</span>
                            <span class="text-xs text-gray-500">Jenjang Akademik</span>
                        </div>
                    </button>

                    <button onclick="updateChart('service')" id="btn-service" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
                        <div class="bg-teal-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Masa Kerja</span>
                            <span class="text-xs text-gray-500">Lama Pengabdian</span>
                        </div>
                    </button>

                    <button onclick="updateChart('age')" id="btn-age" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
                        <div class="bg-purple-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-user-clock text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Usia</span>
                            <span class="text-xs text-gray-500">Kelompok Umur</span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-xl shadow-md p-5 text-center">
                <h4 class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Karyawan</h4>
                <p class="text-3xl font-extrabold text-gray-800">{{ $educationData->sum() }}</p>
            </div>
        </div>

        {{-- KOLOM KANAN: AREA GRAFIK --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 h-full min-h-[550px] flex flex-col">
                <div class="mb-4">
                    <h3 id="chart-title" class="text-lg font-bold text-gray-800 text-center">Persentase Tingkat Pendidikan</h3>
                </div>
                
                {{-- Container Canvas Responsif --}}
                <div class="flex-grow relative w-full flex items-center justify-center">
                    {{-- ID diganti untuk ECharts --}}
                    <div id="echart-container" class="w-full h-[450px]"></div>
                </div>
                
                <div class="mt-2 text-center text-xs text-gray-400">
                    * Grafik interaktif: Arahkan kursor untuk detail
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Load Apache ECharts dari CDN (Sangat Ringan & Cepat) --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<script>
    // --- 1. Persiapan Data ---
    // Fungsi helper untuk mengubah format data PHP (Arrays) ke format ECharts (Object {name, value})
    function formatData(keys, values) {
        return keys.map((key, index) => {
            return { value: values[index], name: key };
        });
    }

    const rawData = {
        education: {
            data: formatData({!! json_encode($educationData->keys()) !!}, {!! json_encode($educationData->values()) !!}),
            title: 'Persentase Tingkat Pendidikan',
            color: ['#3B82F6', '#60A5FA', '#93C5FD', '#1D4ED8', '#1E40AF'] // Nuansa Biru
        },
        service: {
            data: formatData({!! json_encode($serviceData->keys()) !!}, {!! json_encode($serviceData->values()) !!}),
            title: 'Persentase Masa Kerja',
            color: ['#10B981', '#34D399', '#6EE7B7', '#047857', '#065F46'] // Nuansa Hijau Teal
        },
        age: {
            data: formatData({!! json_encode($ageData->keys()) !!}, {!! json_encode($ageData->values()) !!}),
            title: 'Persentase Tingkat Usia',
            color: ['#8B5CF6', '#A78BFA', '#C4B5FD', '#6D28D9', '#5B21B6'] // Nuansa Ungu
        }
    };

    // Inisialisasi Chart
    let myChart = echarts.init(document.getElementById('echart-container'));

    // --- 2. Konfigurasi Responsif ---
    // Agar chart otomatis resize saat layar diubah ukurannya
    window.addEventListener('resize', function() {
        myChart.resize();
    });

    function renderChart(type) {
        const dataset = rawData[type];
        
        // Update Judul
        document.getElementById('chart-title').innerText = dataset.title;

        // --- 3. Konfigurasi ECharts (The Magic Part) ---
        const option = {
            // Tooltip saat hover
            tooltip: {
                trigger: 'item',
                formatter: '{b}: <br/><b>{c} Orang</b> ({d}%)',
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                borderColor: '#eee',
                borderWidth: 1,
                textStyle: { color: '#333' }
            },
            
            // Legend (Keterangan Warna) di bawah
            legend: {
                bottom: '0%',
                left: 'center',
                itemGap: 20,
                textStyle: { fontSize: 12, color: '#666' }
            },

            series: [
                {
                    name: dataset.title,
                    type: 'pie',
                    
                    // Membuat efek Donut (Bolong tengah)
                    radius: ['40%', '65%'], 
                    
                    // Posisi Chart
                    center: ['50%', '45%'], 

                    // Fitur Anti Tumpah Tindih (Avoid Overlap)
                    avoidLabelOverlap: true,
                    
                    // Style tiap potongan
                    itemStyle: {
                        borderRadius: 8,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    
                    // Konfigurasi Label (Angka & Garis)
                    label: {
                        show: true,
                        position: 'outside', // Label di luar
                        formatter: '{b}\n{d}%', // Tampilkan Nama & Persen
                        fontWeight: 'bold',
                        fontSize: 13,
                        color: '#4B5563',
                        padding: [0, -10], // Tweak padding
                        lineHeight: 18
                    },
                    
                    // Konfigurasi Garis Penunjuk
                    labelLine: {
                        show: true,
                        length: 20,  // Panjang garis segmen 1
                        length2: 30, // Panjang garis segmen 2 (yang mendatar)
                        smooth: true // Garis agak melengkung estetik
                    },

                    // Data
                    data: dataset.data,
                    
                    // Warna Custom per kategori
                    color: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
                        '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'
                    ],

                    // Animasi Masuk
                    animationType: 'scale',
                    animationEasing: 'elasticOut',
                    animationDelay: function (idx) {
                        return Math.random() * 200;
                    }
                }
            ]
        };

        // Render Opsi ke Chart
        // notMerge: true memastikan chart bersih total sebelum gambar baru (animasi ulang)
        myChart.setOption(option, { notMerge: true });
    }

    function updateChart(type) {
        // Reset Style Tombol (Sama seperti sebelumnya)
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-500', 'bg-teal-50', 'text-teal-700', 'border-teal-500', 'bg-purple-50', 'text-purple-700', 'border-purple-500');
            btn.classList.add('border-transparent', 'hover:bg-gray-50');
        });

        const btn = document.getElementById('btn-' + type);
        btn.classList.remove('border-transparent', 'hover:bg-gray-50');
        
        if(type === 'education') btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-500');
        if(type === 'service') btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-500');
        if(type === 'age') btn.classList.add('bg-purple-50', 'text-purple-700', 'border-purple-500');

        renderChart(type);
    }

    // Load Default
    document.addEventListener('DOMContentLoaded', () => {
        renderChart('education');
    });
</script>
@endsection