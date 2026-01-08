@extends('layouts.app') {{-- PERBAIKAN: Gunakan layout app secara langsung --}}

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-qrcode mr-3"></i> QR Code Absensi
            </h2>
        </div>

        <div class="p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $event->title }}</h3>
            <p class="text-gray-500 mb-6">Scan QR Code ini untuk mengisi absensi</p>

            <div class="flex justify-center mb-8">
                {{-- ID qr-container dibutuhkan oleh script JS di bawah --}}
                <div id="qr-container" class="p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm inline-block">
                    {!! QrCode::size(400)
                            ->format('svg')
                            ->margin(2) 
                            ->backgroundColor(255, 255, 255)
                            ->generate($url) 
                    !!}
                </div>
            </div>

            <div class="flex justify-center space-x-4">
                {{-- Tombol Download PNG --}}
                <button onclick="downloadQrAsPng()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center cursor-pointer">
                    <i class="fas fa-download mr-2"></i> Unduh PNG
                </button>
                
                {{-- Tombol Kembali (Logika rute tetap aman) --}}
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg shadow transition">
                    Kembali
                </a>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 text-center text-sm text-gray-500 border-t">
            Link Alternatif: <a href="{{ $url }}" class="text-blue-500 hover:underline" target="_blank">{{ $url }}</a>
        </div>
    </div>
</div>

<script>
function downloadQrAsPng() {
    // 1. Ambil elemen SVG
    const svgElement = document.querySelector('#qr-container svg');
    
    if (!svgElement) {
        alert('QR Code belum dimuat sepenuhnya. Silakan refresh halaman.');
        return;
    }

    // 2. Serialisasi SVG ke String
    const serializer = new XMLSerializer();
    const svgString = serializer.serializeToString(svgElement);
    
    // 3. Buat Image Object
    const img = new Image();
    const svgBlob = new Blob([svgString], {type: 'image/svg+xml;charset=utf-8'});
    const url = URL.createObjectURL(svgBlob);
    
    img.onload = function() {
        // Konfigurasi Border Putih (Frame)
        const borderSize = 20; 
        
        // Ambil ukuran asli dari atribut SVG atau default ke 400
        const svgWidth = parseInt(svgElement.getAttribute('width')) || 400;
        const svgHeight = parseInt(svgElement.getAttribute('height')) || 400;

        // 4. Siapkan Canvas (Ukuran SVG + Border)
        const canvas = document.createElement('canvas');
        canvas.width = svgWidth + (borderSize * 2);
        canvas.height = svgHeight + (borderSize * 2);
        
        const ctx = canvas.getContext('2d');
        
        // 5. Warna Background Putih
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // 6. Gambar SVG di tengah Canvas
        ctx.drawImage(img, borderSize, borderSize);
        
        // 7. Download
        const pngUrl = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        downloadLink.href = pngUrl;
        // Gunakan nama file yang aman (slug)
        downloadLink.download = 'qrcode-{{ \Illuminate\Support\Str::slug($event->title) }}.png';
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        
        URL.revokeObjectURL(url);
    };
    
    img.src = url;
}
</script>
@endsection