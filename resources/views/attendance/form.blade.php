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
        <div class="bg-blue-600 p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ $event->title }}</h1>
            <div class="text-sm space-y-1">
                <p>📅 {{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM Y') }}</p>
                <p>⏰ {{ $event->time }}</p>
                <p>📍 {{ $event->location }}</p>
            </div>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('attendance.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan Nama Anda">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Unit Kerja</label>
                    <input type="text" name="work_unit" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Misal: IT Support">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanda Tangan</label>
                    
                    <div class="flex space-x-4 mb-3">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="signature_type" value="draw" checked onclick="toggleSignature('draw')" class="form-radio text-blue-600">
                            <span>Gambar Langsung</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="signature_type" value="upload" onclick="toggleSignature('upload')" class="form-radio text-blue-600">
                            <span>Upload Foto</span>
                        </label>
                    </div>

                    <div id="draw-section" class="border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 text-center">
                        <canvas id="signature-pad" class="border border-gray-300 bg-white mx-auto shadow-sm touch-none" width="400" height="200"></canvas>
                        <input type="hidden" name="signature_draw" id="signature-input">
                        <button type="button" id="clear-pad" class="mt-2 text-sm text-red-600 hover:text-red-800">Hapus / Ulangi</button>
                    </div>

                    <div id="upload-section" class="hidden border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 text-center">
                        <input type="file" name="signature_upload" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maks 2MB.</p>
                    </div>
                </div>

                <button type="submit" onclick="prepareSubmission()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Simpan Absensi
                </button>
            </form>

            <div class="mt-8 pt-6 border-t text-center">
                <a href="{{ route('attendance.pdf', $event->id) }}" class="text-sm text-gray-600 underline">Download Rekap PDF (Admin)</a>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)' // Background putih agar tidak transparan saat disimpan
        });

        // Handle Resize Canvas agar responsif di HP
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Hapus saat resize agar tidak pecah
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        // Tombol Clear
        document.getElementById('clear-pad').addEventListener('click', function () {
            signaturePad.clear();
        });

        // Toggle antara Gambar dan Upload
        function toggleSignature(type) {
            if (type === 'draw') {
                document.getElementById('draw-section').classList.remove('hidden');
                document.getElementById('upload-section').classList.add('hidden');
            } else {
                document.getElementById('draw-section').classList.add('hidden');
                document.getElementById('upload-section').classList.remove('hidden');
            }
        }

        // Sebelum Submit, masukkan data gambar ke input hidden
        function prepareSubmission() {
            const signatureType = document.querySelector('input[name="signature_type"]:checked').value;
            if (signatureType === 'draw') {
                if (signaturePad.isEmpty()) {
                    alert("Silahkan tanda tangan terlebih dahulu!");
                    event.preventDefault();
                } else {
                    const data = signaturePad.toDataURL('image/png');
                    document.getElementById('signature-input').value = data;
                }
            }
        }
    </script>
</body>
</html>