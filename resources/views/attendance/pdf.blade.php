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

        /* Styling Info Acara */
        .event-info { margin-bottom: 20px; text-align: left; }
        .event-info tr td { padding: 3px 0; font-size: 13px; }
        .label { width: 130px; font-weight: bold; }

        /* Styling Tabel Absensi */
        .attendance-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .attendance-table th, .attendance-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .attendance-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
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
                <td class="label">Nama Acara</td>
                <td>: {{ $event->title }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($event->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td>: {{ $event->start_time }} - {{ $event->end_time }} WITA</td>
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
                <th style="width: 35%">Nama Peserta</th>
                <th style="width: 25%">Unit Kerja / Instansi</th>
                <th style="width: 20%">Waktu Hadir</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{-- PERBAIKAN: Ambil langsung dari field 'name' tabel attendance --}}
                    {{ $attendance->name }}
                </td>
                <td>
                    {{-- PERBAIKAN: Ambil langsung dari field 'work_unit' tabel attendance --}}
                    {{ $attendance->work_unit }}
                </td>
                <td class="text-center">
                    {{-- Gunakan created_at jika check_in_time kosong --}}
                    {{ \Carbon\Carbon::parse($attendance->created_at)->format('H:i') }}
                </td>
                <td class="text-center">Hadir</td>
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