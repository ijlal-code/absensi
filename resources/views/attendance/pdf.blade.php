<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .signature-img { height: 50px; max-width: 100px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR</h2>
        <h3>{{ $event->title }}</h3>
        <p>
            Hari/Tanggal: {{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM Y') }}<br>
            Waktu: {{ $event->time }}<br>
            Tempat: {{ $event->location }}
        </p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 35%">Nama</th>
                <th style="width: 30%">Unit Kerja</th>
                <th style="width: 30%">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $attendance->name }}</td>
                <td>{{ $attendance->work_unit }}</td>
                <td style="text-align: center">
                    @if($attendance->signature_path)
                        <img src="{{ public_path('storage/' . $attendance->signature_path) }}" class="signature-img">
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>