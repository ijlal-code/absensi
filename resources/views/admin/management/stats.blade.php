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
                        <div class="bg-indigo-500 text-white rounded-full w-8 h-8 flex-shrink-0 flex items-center justify-center mr-3">
                            <i class="fas fa-user-clock text-sm"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold text-sm">Usia</span>
                            <span class="text-xs text-gray-500">Kelompok Umur</span>
                        </div>
                    </button>

                    {{-- Tombol Gender dengan icon warna Pink/Biru --}}
                    <button onclick="updateChart('gender')" id="btn-gender" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition w-full">
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
                <h4 class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Karyawan</h4>
                <p class="text-4xl font-extrabold text-gray-800">{{ $educationData->sum() }}</p>
                <span class="text-xs text-green-500 font-semibold"><i class="fas fa-check-circle"></i> Data Terupdate</span>
            </div>
        </div>

        {{-- KOLOM KANAN: AREA GRAFIK --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 h-full min-h-[550px] flex flex-col relative">
                <div class="mb-4 flex justify-between items-start">
                    <h3 id="chart-title" class="text-xl font-bold text-gray-800">Persentase Tingkat Pendidikan</h3>
                    <div class="bg-gray-100 rounded p-1">
                        <i class="fas fa-chart-pie text-gray-400"></i>
                    </div>
                </div>
                
                {{-- Container Canvas Responsif --}}
                <div class="flex-grow relative w-full flex items-center justify-center">
                    <div id="echart-container" class="w-full h-[450px]"></div>
                </div>
                
                <div class="mt-2 text-center text-xs text-gray-400 border-t pt-2">
                    * Arahkan kursor pada grafik untuk melihat detail jumlah orang.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<script>
    // --- 1. Persiapan Data ---
    function formatData(keys, values) {
        return keys.map((key, index) => {
            return { value: values[index], name: key };
        });
    }

    // Definisi Warna Custom Profesional
    const customColors = {
        // Gender: Biru untuk Laki-laki, Pink untuk Perempuan
        'Laki-laki': '#3B82F6', 
        'Pria': '#3B82F6',
        'Male': '#3B82F6',
        
        'Perempuan': '#EC4899', 
        'Wanita': '#EC4899',
        'Female': '#EC4899',

        'Tidak Diketahui': '#9CA3AF'
    };

    const rawData = {
        education: {
            data: formatData({!! json_encode($educationData->keys()) !!}, {!! json_encode($educationData->values()) !!}),
            title: 'Persentase Tingkat Pendidikan',
            // Gradasi Biru Profesional
            color: ['#1E3A8A', '#1D4ED8', '#2563EB', '#3B82F6', '#60A5FA', '#93C5FD'] 
        },
        service: {
            data: formatData({!! json_encode($serviceData->keys()) !!}, {!! json_encode($serviceData->values()) !!}),
            title: 'Persentase Masa Kerja',
            // Gradasi Teal/Emerald
            color: ['#064E3B', '#065F46', '#047857', '#059669', '#10B981', '#34D399', '#6EE7B7']
        },
        age: {
            data: formatData({!! json_encode($ageData->keys()) !!}, {!! json_encode($ageData->values()) !!}),
            title: 'Persentase Tingkat Usia',
            // Gradasi Indigo/Ungu
            color: ['#312E81', '#4338CA', '#4F46E5', '#6366F1', '#818CF8', '#A5B4FC']
        },
        gender: {
            data: formatData({!! json_encode($genderData->keys()) !!}, {!! json_encode($genderData->values()) !!}),
            title: 'Persentase Jenis Kelamin',
            // Warna akan di-override logic di bawah, ini fallback
            color: ['#3B82F6', '#EC4899'] 
        }
    };

    let myChart = echarts.init(document.getElementById('echart-container'));

    window.addEventListener('resize', function() {
        myChart.resize();
    });

    function renderChart(type) {
        const dataset = rawData[type];
        
        // Update Judul
        document.getElementById('chart-title').innerText = dataset.title;

        // LOGIKA KHUSUS UNTUK WARNA GENDER
        let chartColors = dataset.color;
        let chartData = dataset.data;

        if (type === 'gender') {
            // Jika gender, paksa warna sesuai nama key (Laki=Biru, Pr=Pink)
            chartData = chartData.map(item => {
                return {
                    value: item.value,
                    name: item.name,
                    itemStyle: {
                        color: customColors[item.name] || '#9CA3AF' // Fallback abu-abu
                    }
                };
            });
        }

        const option = {
            tooltip: {
                trigger: 'item',
                formatter: '{b}: <br/><b>{c} Orang</b> ({d}%)',
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                textStyle: { color: '#1f2937' },
                padding: 10,
                extraCssText: 'box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);'
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
                    radius: ['45%', '70%'], // Donut style lebih tebal
                    center: ['50%', '45%'],
                    avoidLabelOverlap: true,
                    itemStyle: {
                        borderRadius: 6,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: true,
                        position: 'outside',
                        formatter: '{b}\n{d}%',
                        fontWeight: '600',
                        fontSize: 13,
                        color: '#374151'
                    },
                    labelLine: {
                        show: true,
                        length: 15,
                        length2: 25,
                        smooth: true
                    },
                    data: chartData,
                    
                    // Gunakan warna default jika bukan gender (gender sudah di-override di atas)
                    color: (type !== 'gender') ? chartColors : undefined,

                    animationType: 'scale',
                    animationEasing: 'elasticOut',
                    animationDelay: function (idx) {
                        return Math.random() * 200;
                    }
                }
            ]
        };

        myChart.setOption(option, { notMerge: true });
    }

    function updateChart(type) {
        // Reset Style Tombol
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove(
                'bg-blue-50', 'text-blue-700', 'border-blue-500', 
                'bg-teal-50', 'text-teal-700', 'border-teal-500', 
                'bg-indigo-50', 'text-indigo-700', 'border-indigo-500', // Age (Ungu)
                'bg-pink-50', 'text-pink-700', 'border-pink-500', // Gender (Pink base)
                'active'
            );
            btn.classList.add('border-transparent', 'hover:bg-gray-50');
            // Reset icon colors
        });

        const btn = document.getElementById('btn-' + type);
        btn.classList.remove('border-transparent', 'hover:bg-gray-50');
        btn.classList.add('active');
        
        if(type === 'education') btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-500');
        if(type === 'service') btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-500');
        if(type === 'age') btn.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-500');
        // Khusus gender kita kasih style pink-ish tapi kontennya gradasi
        if(type === 'gender') btn.classList.add('bg-pink-50', 'text-pink-700', 'border-pink-500');

        renderChart(type);
    }

    // Load Default
    document.addEventListener('DOMContentLoaded', () => {
        renderChart('education');
    });
</script>
@endsection