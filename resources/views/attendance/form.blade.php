<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - {{ $event->title }}</title>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Signature Pad Library --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    
    <style>
        /* CSS Tambahan untuk memastikan Canvas tidak bisa di-scroll saat disentuh */
        canvas {
            touch-action: none;
            width: 100% !important; /* Paksa lebar 100% */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-6 px-4 flex items-center justify-center">

    <div class="w-full max-w-lg bg-white shadow-xl rounded-lg overflow-hidden">
        {{-- Header Agenda --}}
        <div class="bg-blue-900 p-6 text-white relative">
            <h1 class="text-xl md:text-2xl font-bold mb-3 leading-tight">{{ $event->title }}</h1>
            <div class="text-sm space-y-2 opacity-90">
                <p class="flex items-start"><i class="fas fa-calendar mt-1 mr-2 w-4"></i>{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM Y') }}</p>
                <p class="flex items-start"><i class="fas fa-clock mt-1 mr-2 w-4"></i>{{ $event->start_time }} - {{ $event->end_time }} WITA</p>
                <p class="flex items-start"><i class="fas fa-map-marker-alt mt-1 mr-2 w-4"></i>{{ $event->location }}</p>
            </div>
        </div>

        <div class="p-6">
            {{-- Pesan Error / Sukses --}}
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm" role="alert">
                    <p class="font-bold">Perhatian!</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 text-center">
                    <p class="font-bold text-lg">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-center">
                    <p class="font-bold">Maaf!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Form Absensi --}}
            <form id="attendanceForm" action="{{ route('attendance.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="name" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan nama Anda" value="{{ old('name') }}">
                    </div>
                </div>

                {{-- Unit Kerja --}}
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Unit Kerja / Instansi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-building"></i>
                        </span>
                        <input type="text" name="work_unit" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Staff IT / Tamu" value="{{ old('work_unit') }}">
                    </div>
                </div>

                {{-- Pilihan Tanda Tangan --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanda Tangan</label>
                    
                    <div class="flex space-x-3 mb-3">
                        <label class="flex-1 flex items-center justify-center space-x-2 cursor-pointer bg-gray-50 p-2 rounded border border-gray-200 hover:bg-blue-50 transition">
                            <input type="radio" name="signature_type" value="draw" checked onclick="toggleSignature('draw')" class="form-radio text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Gambar</span>
                        </label>
                        <label class="flex-1 flex items-center justify-center space-x-2 cursor-pointer bg-gray-50 p-2 rounded border border-gray-200 hover:bg-blue-50 transition">
                            <input type="radio" name="signature_type" value="upload" onclick="toggleSignature('upload')" class="form-radio text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Upload</span>
                        </label>
                    </div>

                    {{-- AREA GAMBAR TANDA TANGAN (Canvas) --}}
                    <div id="draw-section" class="border-2 border-dashed border-gray-300 rounded-lg p-1 bg-gray-50">
                        <div class="text-xs text-gray-400 text-center mb-1">Silakan tanda tangan di kotak bawah ini</div>
                        
                        <div id="signature-pad-container" class="relative w-full bg-white rounded shadow-sm overflow-hidden" style="height: 200px;">
                            <canvas id="signature-pad" class="absolute left-0 top-0 w-full h-full cursor-crosshair touch-none"></canvas>
                        </div>
                        
                        <input type="hidden" name="signature_draw" id="signature-input">
                        
                        <div class="mt-2 text-right">
                            <button type="button" id="clear-pad" class="text-xs bg-red-100 text-red-600 px-3 py-1.5 rounded hover:bg-red-200 transition flex items-center ml-auto">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>

                    {{-- AREA UPLOAD FOTO --}}
                    <div id="upload-section" class="hidden border-2 border-dashed border-gray-300 rounded-lg p-8 bg-gray-50 text-center">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <div class="text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Pilih File Gambar</span>
                                    <input id="file-upload" name="signature_upload" type="file" class="sr-only" accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG (Maks. 5MB)</p>
                            <p id="filename-display" class="text-xs text-gray-700 font-bold mt-2"></p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg flex justify-center items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Simpan Absensi
                </button>
            </form>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} E-Absensi System
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // --- Inisialisasi Variabel ---
        const canvas = document.getElementById('signature-pad');
        const container = document.getElementById('signature-pad-container');
        const signaturePad = new SignaturePad(canvas, { 
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // --- Fungsi Resize Canvas Responsif ---
        function resizeCanvas() {
            // Simpan data gambar saat ini agar tidak hilang saat resize (opsional, tapi bagus UX-nya)
            const data = signaturePad.toData();

            // Hitung rasio piksel perangkat untuk ketajaman di layar HP/Retina
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            
            // Set lebar canvas sesuai lebar container parent-nya
            canvas.width = container.offsetWidth * ratio;
            canvas.height = container.offsetHeight * ratio;
            
            // Scale context agar gambar tidak mengecil
            canvas.getContext("2d").scale(ratio, ratio);

            // Bersihkan dan kembalikan data (jika ada), atau biarkan kosong jika resize ekstrim
            signaturePad.clear(); 
            // Jika ingin mempertahankan coretan saat rotate HP, uncomment baris bawah:
            // signaturePad.fromData(data); 
        }

        // Panggil resize saat load awal dan saat window di-resize (rotate HP)
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        // --- Tombol Hapus ---
        document.getElementById('clear-pad').addEventListener('click', function() {
            signaturePad.clear();
        });

        // --- Toggle Tipe Tanda Tangan (Gambar vs Upload) ---
        window.toggleSignature = function(type) {
            document.getElementById('draw-section').classList.toggle('hidden', type !== 'draw');
            document.getElementById('upload-section').classList.toggle('hidden', type !== 'upload');
            
            // Jika pindah ke draw, resize lagi untuk memastikan ukuran pas
            if (type === 'draw') {
                resizeCanvas();
            }
        }

        // --- Tampilkan Nama File saat Upload ---
        const fileInput = document.getElementById('file-upload');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('filename-display').innerText = fileName ? 'File: ' + fileName : '';
        });

        // --- Validasi Form Sebelum Submit ---
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            const type = document.querySelector('input[name="signature_type"]:checked').value;
            
            if (type === 'draw') {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    // Alert sederhana (bisa diganti sweetalert jika mau)
                    alert("Mohon tanda tangan terlebih dahulu pada kotak yang tersedia.");
                } else {
                    // Masukkan data Base64 ke hidden input
                    document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
                }
            } else {
                // Validasi upload
                if (fileInput.files.length === 0) {
                    e.preventDefault();
                    alert("Mohon pilih file foto tanda tangan Anda.");
                }
            }
        });
    </script>
</body>
</html>