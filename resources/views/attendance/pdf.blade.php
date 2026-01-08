<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* Styling Header */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-logo-left { text-align: left; width: 15%; }
        .header-logo-right { text-align: right; width: 15%; }
        .header-title { text-align: center; width: 70%; vertical-align: middle; }
        .header-title h2 { margin: 0; text-transform: uppercase; font-size: 18px; font-weight: bold; }
        .header-img { height: 60px; width: auto; }

        /* Styling Info Agenda */
        .event-info { margin-bottom: 20px; text-align: left; }
        .event-info tr td { padding: 3px 0; font-size: 13px; }
        .label { width: 130px; font-weight: bold; }

        /* Styling Tabel Absensi */
        .attendance-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .attendance-table th, .attendance-table td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: middle; }
        .attendance-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        
        /* Styling Tanda Tangan di Tabel */
        .signature-img {
            height: 30px; /* Tinggi tanda tangan dibatasi agar baris tidak terlalu besar */
            width: auto;
            max-width: 80px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-logo-left">
                <img src="{{ public_path('img/logo-tonasa.png') }}" class="header-img" alt="Tonasa">
            </td>
            <td class="header-title">
                <h2>DAFTAR HADIR</h2>
            </td>
            <td class="header-logo-right">
                <img src="{{ public_path('img/logo-internal-audit.png') }}" class="header-img" alt="Audit">
            </td>
        </tr>
    </table>

    <div class="event-info">
        <table style="width: 100%; border: none;">
            <tr>
                <td class="label">Agenda</td>
                <td>: {{ $event->title }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                {{-- Format Tanggal Bahasa Indonesia --}}
                <td>: {{ \Carbon\Carbon::parse($event->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td>: {{ $event->start_time }} - {{ $event->end_time }} Wita</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td>: {{ $event->location }}</td>
            </tr>
        </table>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 30%">Nama Peserta</th>
                <th style="width: 25%">Unit Kerja / Instansi</th>
                <th style="width: 20%">Waktu Hadir</th>
                {{-- Ubah Header Status Menjadi Tanda Tangan --}}
                <th style="width: 20%">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $attendance->name }}</td>
                <td>{{ $attendance->work_unit }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($attendance->created_at)->format('H:i') }}
                </td>
                <td class="text-center">
                    {{-- Logika Menampilkan Gambar Tanda Tangan --}}
                    @php
                        // Kita gunakan storage_path untuk mengambil file langsung dari folder storage/app/public
                        $signaturePath = storage_path('app/public/' . $attendance->signature_path);
                    @endphp

                    @if($attendance->signature_path && file_exists($signaturePath))
                        <img src="{{ $signaturePath }}" class="signature-img" alt="TTD">
                    @else
                        <span style="font-size: 10px; color: #888;">(Tidak Ada)</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada peserta yang hadir.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; font-weight: bold;">
        Total Peserta: {{ $attendances->count() }}
    </div>

</body>
</html>