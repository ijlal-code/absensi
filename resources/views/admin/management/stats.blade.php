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
                <div class="flex-grow relative w-full flex items-center justify-center overflow-visible">
                    {{-- PENTING: Container harus cukup tinggi untuk menampung padding chart --}}
                    <div class="relative h-[450px] w-full">
                        <canvas id="employeeChart"></canvas>
                    </div>
                </div>
                
                <div class="mt-2 text-center text-xs text-gray-400">
                    * Data ditampilkan dalam persentase
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Load Chart.js dan Plugin Datalabels --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    // --- KONFIGURASI GLOBAL ---
    // State untuk mengontrol animasi garis & label
    let chartState = {
        isAnimationComplete: false
    };

    // 1. Plugin Custom untuk Menggambar Garis Konektor
    const linePlugin = {
        id: 'linePlugin',
        afterDraw: (chart) => {
            // Hanya gambar garis jika animasi putaran chart sudah selesai
            if (!chartState.isAnimationComplete) return;

            const ctx = chart.ctx;
            ctx.save();
            
            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                meta.data.forEach((element, index) => {
                    // Jangan gambar jika slice disembunyikan (misal via legend)
                    if (element.hidden) return;

                    // Jangan gambar jika labelnya disembunyikan otomatis oleh 'display: auto'
                    // Kita cek apakah datalabels menyembunyikannya (visible is usually a property of the label context, 
                    // but simple overlapping check: if slice is too small, skip line)
                    if(element.circumference < 0.05) return; // Skip garis untuk slice sangat tipis (< 1%)

                    const model = element;
                    const midAngle = (model.startAngle + model.endAngle) / 2;
                    
                    // Koordinat Pusat dan Jari-jari
                    const r = model.outerRadius;
                    const x = model.x;
                    const y = model.y;
                    
                    // --- LOGIKA ANTI TUMPAH TINDIH (Garis vs Teks) ---
                    // Panjang garis kita set FIX: 25px keluar dari lingkaran
                    const lineLength = 25; 
                    
                    // Titik Awal (Pinggir Lingkaran)
                    const xStart = x + Math.cos(midAngle) * r;
                    const yStart = y + Math.sin(midAngle) * r;
                    
                    // Titik Akhir (Ujung Garis)
                    const xEnd = x + Math.cos(midAngle) * (r + lineLength);
                    const yEnd = y + Math.sin(midAngle) * (r + lineLength);
                    
                    ctx.beginPath();
                    ctx.moveTo(xStart, yStart);
                    ctx.lineTo(xEnd, yEnd);
                    ctx.lineWidth = 1.5;
                    ctx.strokeStyle = dataset.backgroundColor[index];
                    ctx.stroke();

                    // Titik kecil di ujung garis
                    ctx.beginPath();
                    ctx.arc(xEnd, yEnd, 2, 0, 2 * Math.PI);
                    ctx.fillStyle = dataset.backgroundColor[index];
                    ctx.fill();
                });
            });
            ctx.restore();
        }
    };

    // Registrasi Plugin
    Chart.register(ChartDataLabels, linePlugin);

    // Data dari Controller
    const rawData = {
        education: {
            labels: {!! json_encode($educationData->keys()) !!},
            data: {!! json_encode($educationData->values()) !!},
            title: 'Persentase Tingkat Pendidikan'
        },
        service: {
            labels: {!! json_encode($serviceData->keys()) !!},
            data: {!! json_encode($serviceData->values()) !!},
            title: 'Persentase Masa Kerja'
        },
        age: {
            labels: {!! json_encode($ageData->keys()) !!},
            data: {!! json_encode($ageData->values()) !!},
            title: 'Persentase Tingkat Usia'
        }
    };

    let myChart = null;

    function renderChart(type) {
        const ctx = document.getElementById('employeeChart').getContext('2d');
        const dataset = rawData[type];

        // 1. Hapus Chart Lama (PENTING untuk Animasi Ulang)
        if (myChart) {
            myChart.destroy();
        }

        // 2. Reset Status Animasi
        chartState.isAnimationComplete = false;

        const bgColors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
            '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'
        ];

        myChart = new Chart(ctx, {
            type: 'pie', // Gunakan Pie agar garis konektor terlihat jelas dari pusat
            data: {
                labels: dataset.labels,
                datasets: [{
                    data: dataset.data,
                    backgroundColor: bgColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                
                // --- PENGATURAN LAYOUT AGAR TIDAK HILANG/TERPOTONG ---
                layout: {
                    padding: {
                        top: 40,
                        bottom: 50, // Jarak ke legend bawah
                        left: 80,   // Ruang untuk label kiri
                        right: 80   // Ruang untuk label kanan
                    }
                },

                // --- ANIMASI HALUS ---
                animation: {
                    duration: 1200, // Durasi sedikit lebih lambat agar elegan
                    easing: 'easeOutQuart',
                    animateScale: true,
                    animateRotate: true,
                    // Callback saat animasi putaran selesai
                    onComplete: function(animation) {
                        if (!chartState.isAnimationComplete) {
                            chartState.isAnimationComplete = true;
                            // Trigger update tanpa animasi (mode 'none') untuk memunculkan garis & label
                            animation.chart.update('none'); 
                        }
                    }
                },
                
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: { enabled: true },
                    
                    // --- KONFIGURASI LABEL ANGKA ---
                    datalabels: {
                        // Tampilkan hanya setelah animasi selesai
                        opacity: (context) => {
                            return chartState.isAnimationComplete ? 1 : 0;
                        },
                        
                        // ANTI TUMPAH TINDIH (AUTO HIDE)
                        display: 'auto', 

                        color: '#4B5563', 
                        anchor: 'end', 
                        align: 'end',
                        
                        // Jarak Label dari Slice (Lebih panjang dari garis 25px, jadi aman)
                        offset: 35, 

                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => { sum += data; });
                            // Hitung Persen
                            let percentage = (value * 100 / sum).toFixed(1) + "%";
                            return percentage;
                        },
                        
                        // Styling Label Box
                        backgroundColor: 'rgba(255,255,255,0.9)', 
                        borderRadius: 4,
                        padding: 4,
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        
                        // Bayangan agar menonjol di atas garis grid (jika ada)
                        listeners: {
                            enter: function(context) {
                                context.hovered = true;
                                return true;
                            },
                            leave: function(context) {
                                context.hovered = false;
                                return true;
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('chart-title').innerText = dataset.title;
    }

    function updateChart(type) {
        // Reset Style Tombol
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

    document.addEventListener('DOMContentLoaded', () => {
        renderChart('education');
    });
</script>
@endsection