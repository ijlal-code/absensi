<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

    <div class="max-w-2xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-blue-900 p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ $event->title }}</h1>
            <div class="text-sm space-y-1">
                <p><i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM Y') }}</p>
                <p><i class="fas fa-clock mr-2"></i>{{ $event->start_time }} - {{ $event->end_time }}</p>
                <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->location }}</p>
            </div>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                    <h3 class="font-bold text-lg">Berhasil!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form id="attendanceForm" action="{{ route('attendance.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Nama Anda" value="{{ old('name') }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Unit Kerja / Instansi</label>
                    <input type="text" name="work_unit" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Staff IT / Umum" value="{{ old('work_unit') }}">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanda Tangan</label>
                    
                    <div class="flex space-x-4 mb-3">
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-3 py-2 rounded border hover:bg-gray-100">
                            <input type="radio" name="signature_type" value="draw" checked onclick="toggleSignature('draw')" class="form-radio text-blue-600">
                            <span class="text-sm font-medium">Gambar Langsung</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-3 py-2 rounded border hover:bg-gray-100">
                            <input type="radio" name="signature_type" value="upload" onclick="toggleSignature('upload')" class="form-radio text-blue-600">
                            <span class="text-sm font-medium">Upload Foto</span>
                        </label>
                    </div>

                    <div id="draw-section" class="border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 text-center">
                        <p class="text-xs text-gray-400 mb-2">Silahkan tanda tangan di dalam kotak ini</p>
                        <canvas id="signature-pad" class="border border-gray-300 bg-white mx-auto shadow-sm touch-none rounded" width="400" height="200"></canvas>
                        <input type="hidden" name="signature_draw" id="signature-input">
                        <button type="button" id="clear-pad" class="mt-2 text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Hapus & Ulangi</button>
                    </div>

                    <div id="upload-section" class="hidden border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 text-center">
                        <input type="file" name="signature_upload" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maks 5MB.</p>
                    </div>
                </div>

                <button type="submit" id="btn-submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg transform active:scale-95">
                    Simpan Absensi
                </button>
            </form>
        </div>
    </div>

    <script>
        // Setup Canvas
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Responsif Canvas
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            // Jangan hapus konten saat resize jika ada isinya
            const data = signaturePad.toData();
            signaturePad.clear(); 
            signaturePad.fromData(data);
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        document.getElementById('clear-pad').addEventListener('click', function () {
            signaturePad.clear();
        });

        function toggleSignature(type) {
            const drawSec = document.getElementById('draw-section');
            const uploadSec = document.getElementById('upload-section');
            if (type === 'draw') {
                drawSec.classList.remove('hidden');
                uploadSec.classList.add('hidden');
            } else {
                drawSec.classList.add('hidden');
                uploadSec.classList.remove('hidden');
            }
        }

        // --- PERBAIKAN UTAMA: Event Listener Submit ---
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            const signatureType = document.querySelector('input[name="signature_type"]:checked').value;
            
            if (signatureType === 'draw') {
                if (signaturePad.isEmpty()) {
                    e.preventDefault(); // Stop submit
                    alert("Harap tanda tangan terlebih dahulu pada kotak yang disediakan!");
                    return false;
                }
                // Masukkan data Base64 ke input hidden
                const data = signaturePad.toDataURL('image/png');
                document.getElementById('signature-input').value = data;
            }
            // Jika upload, biarkan form submit seperti biasa
        });
    </script>
</body>
</html>