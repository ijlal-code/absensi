<!DOCTYPE html>
<html>
<head>
    <title>Minutes of Meeting</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        /* FRAME LUAR */
        .page {
            border: 6px double #8b0000;
            padding: 14px;
        }

        /* HEADER */
       .header {
    position: relative;
    margin-bottom: 20px;
    height: 80px; /* KUNCI AREA ATAS */
}


        .logo-left {
    position: absolute;
    left: 0;
    top: 5px;      /* PALING ATAS */
    width: 70px;
}

.logo-right {
    position: absolute;
    right: 0;
    top: 5px;      /* SAMA PERSIS */
    width: 60px;
}


        .title-box {
            text-align: center;
            margin-top: 10px;
        }

        .title {
            display: inline-block;
            background: #5e4b7a;
            color: #fff;
            padding: 8px 40px;
            font-size: 16px;
            font-weight: bold;
        }

        .title-shadow {
            width: 260px;
            height: 6px;
            background: #b7a6a6;
            margin: 0 auto;
        }

        /* KOTAK UNGU */
        .box {
            border: 4px solid #6b4ea0;
            margin-bottom: 14px;
        }

        /* INFO RAPAT */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #888;
        }

        .info-label {
            width: 30%;
            background: #e6e1ef;
            font-weight: bold;
            border-right: 1px solid #888;
        }

        /* ACTION ITEMS */
        .action-table {
            width: 100%;
            border-collapse: collapse;
        }

        .action-header td {
            background: #e6e1ef;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #888;
        }

        .action-table td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #888;
        }

        .action-items {
            width: 60%;
        }

        .action-pic {
            width: 20%;
            text-align: center;
            border-left: 1px solid #888;
            border-right: 1px solid #888;
        }

        .action-deadline {
            width: 20%;
            text-align: center;
        }

        /* FOOTER (PERSIS GAMBAR) */
        .footer {
            width: 100%;
            margin-top: 10px;
        }

        .footer-table {
            width: 65%;
            float: left;
            border-collapse: collapse;
        }

        .footer-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .footer-label {
            background: #e6e1ef;
            font-weight: bold;
            width: 35%;
        }

        .notulis-box {
            width: 30%;
            float: right;
            border: 3px solid #6b4ea0;
            padding: 10px;
            background: #e6e1ef;
            height: 100px;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ public_path('img/logo-tonasa.png') }}" class="logo-left">
        <img src="{{ public_path('img/logo-internal-audit.png') }}" class="logo-right">

        <div class="title-box">
            <div class="title">MINUTES OF MEETING</div>
            <div class="title-shadow"></div>
        </div>
    </div>

    {{-- INFO RAPAT --}}
    <div class="box">
        <table class="info-table">
            <tr>
                <td class="info-label">Type of Meeting</td>
                <td>{{ $meeting->type_of_meeting }}</td>
            </tr>
            <tr>
                <td class="info-label">Name of Meeting Facilitator</td>
                <td>{{ $meeting->facilitator }}</td>
            </tr>
            <tr>
                <td class="info-label">Date of Meeting</td>
                <td>{{ \Carbon\Carbon::parse($meeting->date)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Start Time</td>
                <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('H.i') }} WITA</td>
            </tr>
            <tr>
                <td class="info-label">Place of Meeting</td>
                <td>{{ $meeting->location }}</td>
            </tr>
        </table>
    </div>

    {{-- ACTION ITEMS --}}
    <div class="box">
        <table class="action-table">
            <tr class="action-header">
                <td>Action Items</td>
                <td>Pic</td>
                <td>Deadline</td>
            </tr>

            @php
                $currentPic = null;
                $number = 1;
            @endphp

            @foreach($meeting->actionItems as $item)

                @if($currentPic !== null && $currentPic !== $item->pic)
                    <tr>
                        <td colspan="3" style="border-top:1px solid #888;"></td>
                    </tr>
                    @php $number = 1; @endphp
                @endif

                <tr>
                    <td class="action-items">
                        {{ $number }}. {{ $item->action_item }}
                    </td>
                    <td class="action-pic">
                        {{ $item->pic }}
                    </td>
                    <td class="action-deadline">
                        {{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') : '' }}
                    </td>
                </tr>

                @php
                    $currentPic = $item->pic;
                    $number++;
                @endphp

            @endforeach
        </table>
    </div>

    {{-- FOOTER (FINAL PERSIS GAMBAR) --}}
    <div class="footer">

        <table class="footer-table">
            <tr>
                <td class="footer-label">List of Attendees</td>
                <td>Terlampir</td>
            </tr>
            <tr>
                <td class="footer-label">Presenter</td>
                <td>Administrator Utama</td>
            </tr>
            <tr>
                <td class="footer-label">Waktu</td>
                <td>90 Menit</td>
            </tr>
        </table>

        <div class="notulis-box">
            <strong>Notulis,</strong><br><br><br>
            <u><b>Sapriadi,ST</b></u>
        </div>

        <div class="clearfix"></div>
    </div>

</div>

</body>
</html>
