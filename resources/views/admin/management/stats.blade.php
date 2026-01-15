@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistik Karyawan</h2>
            <p class="text-gray-600">Visualisasi data demografi karyawan Semen Tonasa.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: KONTROL GRAFIK --}}
        <div class="md:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Pilih Data Grafik</h3>
                
                <div class="flex flex-col gap-3">
                    <button onclick="updateChart('education')" id="btn-education" class="chart-btn active flex items-center p-3 rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-700 transition">
                        <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Tingkat Pendidikan</span>
                            <span class="text-xs text-gray-500">Distribusi Jenjang Akademik</span>
                        </div>
                    </button>

                    <button onclick="updateChart('service')" id="btn-service" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition">
                        <div class="bg-teal-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Masa Kerja</span>
                            <span class="text-xs text-gray-500">Lama Pengabdian (Tahun)</span>
                        </div>
                    </button>

                    <button onclick="updateChart('age')" id="btn-age" class="chart-btn flex items-center p-3 rounded-lg border-2 border-transparent hover:bg-gray-50 transition">
                        <div class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Tingkat Usia</span>
                            <span class="text-xs text-gray-500">Kelompok Umur Karyawan</span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Summary Card Kecil --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <h4 class="text-sm text-gray-500 uppercase tracking-wide mb-1">Total Karyawan</h4>
                <p class="text-4xl font-extrabold text-gray-800">{{ $educationData->sum() }}</p>
                <p class="text-xs text-green-600 mt-2"><i class="fas fa-check-circle"></i> Data Terupdate</p>
            </div>
        </div>

        {{-- KOLOM KANAN: AREA GRAFIK --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 h-full flex flex-col items-center justify-center relative">
                <h3 id="chart-title" class="text-xl font-bold text-gray-800 mb-6 absolute top-6 left-6">Persentase Tingkat Pendidikan</h3>
                
                <div class="w-full max-w-md" style="position: relative; height: 400px; width: 100%;">
                    <canvas id="employeeChart"></canvas>
                </div>
                
                {{-- Keterangan --}}
                <div class="mt-4 text-center text-sm text-gray-500">
                    * Arahkan kursor ke grafik untuk melihat detail jumlah karyawan.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Persiapan Data dari Controller (di-convert ke JSON)
    const rawData = {
        education: {
            labels: {!! json_encode($educationData->keys()) !!},
            data: {!! json_encode($educationData->values()) !!},
            color: 'rgba(59, 130, 246, 0.7)', // Blue
            title: 'Persentase Tingkat Pendidikan'
        },
        service: {
            labels: {!! json_encode($serviceData->keys()) !!},
            data: {!! json_encode($serviceData->values()) !!},
            color: 'rgba(20, 184, 166, 0.7)', // Teal
            title: 'Persentase Masa Kerja'
        },
        age: {
            labels: {!! json_encode($ageData->keys()) !!},
            data: {!! json_encode($ageData->values()) !!},
            color: 'rgba(168, 85, 247, 0.7)', // Purple
            title: 'Persentase Tingkat Usia'
        }
    };

    let myChart = null;

    // Fungsi Render Chart
    function renderChart(type) {
        const ctx = document.getElementById('employeeChart').getContext('2d');
        const dataset = rawData[type];

        // Hapus chart lama jika ada
        if (myChart) {
            myChart.destroy();
        }

        // Warna-warni dinamis untuk Pie Chart
        const bgColors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
            '#EC4899', '#6366F1', '#14B8A6'
        ];

        myChart = new Chart(ctx, {
            type: 'doughnut', // Bisa diganti 'pie' jika ingin full
            data: {
                labels: dataset.labels,
                datasets: [{
                    data: dataset.data,
                    backgroundColor: bgColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let percentage = Math.round((value / total) * 100) + '%';
                                return label + ': ' + value + ' Orang (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });

        // Update Judul
        document.getElementById('chart-title').innerText = dataset.title;
    }

    // Fungsi Switch Tombol
    function updateChart(type) {
        // Reset Style Tombol
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-500', 'bg-teal-50', 'text-teal-700', 'border-teal-500', 'bg-purple-50', 'text-purple-700', 'border-purple-500');
            btn.classList.add('border-transparent', 'hover:bg-gray-50');
        });

        // Set Style Tombol Aktif
        const btn = document.getElementById('btn-' + type);
        btn.classList.remove('border-transparent', 'hover:bg-gray-50');
        
        if(type === 'education') btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-500');
        if(type === 'service') btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-500');
        if(type === 'age') btn.classList.add('bg-purple-50', 'text-purple-700', 'border-purple-500');

        renderChart(type);
    }

    // Load Default (Education)
    document.addEventListener('DOMContentLoaded', () => {
        renderChart('education');
    });
</script>
@endsection