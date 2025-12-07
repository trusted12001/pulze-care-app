<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rota Print — {{ $rota_period->location->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left {
            max-width: 70%;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtext {
            font-size: 11px;
            color: #4b5563;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
        }

        .badge-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-published {
            background: #dcfce7;
            color: #166534;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            background: #f3f4f6;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 4px;
            vertical-align: top;
        }

        th {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: #4b5563;
            text-align: left;
        }

        .date-cell {
            width: 12%;
            white-space: nowrap;
        }

        .segment-cell {
            width: 29%;
        }

        .date-main {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .date-sub {
            font-size: 10px;
            color: #6b7280;
        }

        .segment-block {
            border-radius: 6px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 4px 4px;
            margin-bottom: 4px;
        }

        .segment-label {
            font-size: 10px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 2px;
        }

        .segment-staff {
            font-size: 10px;
            color: #374151;
        }

        .segment-none {
            font-size: 10px;
            color: #9ca3af;
            font-style: italic;
        }

        .segment-header {
            font-size: 9px;
            color: #6b7280;
            font-weight: 500;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <div class="header-left">
            <div class="title">Rota — {{ $rota_period->location->name }}</div>
            <div class="subtext">
                Period: {{ $rota_period->start_date->format('d M Y') }}
                – {{ $rota_period->end_date->format('d M Y') }}
            </div>
            <div class="subtext">
                Generated on: {{ now('Europe/London')->format('d M Y H:i') }}
            </div>
        </div>
        <div>
            @php
                $status = $rota_period->status ?? 'draft';
            @endphp
            <span class="badge {{ $status === 'published' ? 'badge-published' : 'badge-draft' }}">
                Status: {{ ucfirst($status) }}
            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="date-cell">Date</th>
                <th class="segment-cell">
                    Morning
                    <div class="segment-header">~07:00–14:00</div>
                </th>
                <th class="segment-cell">
                    Afternoon
                    <div class="segment-header">~14:00–21:00</div>
                </th>
                <th class="segment-cell">
                    Night
                    <div class="segment-header">~21:00–07:00</div>
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $segmentOrder = ['morning', 'afternoon', 'night'];
            @endphp
            @forelse($days as $day)
                @php
                    $date = $day['date'];
                    $segments = $day['segments'];
                @endphp
                <tr>
                    {{-- Date --}}
                    <td class="date-cell">
                        <div class="date-main">{{ $date->format('D d M') }}</div>
                        <div class="date-sub">{{ $date->format('Y') }}</div>
                    </td>

                    {{-- Segments --}}
                    @foreach($segmentOrder as $seg)
                        <td class="segment-cell">
                            @if(!empty($segments[$seg]))
                                @foreach($segments[$seg] as $entry)
                                    <div class="segment-block">
                                        <div class="segment-label">{{ $entry['label'] }}</div>
                                        @if(!empty($entry['staff']))
                                            <div class="segment-staff">
                                                {{ implode(', ', $entry['staff']) }}
                                            </div>
                                        @else
                                            <div class="segment-none">
                                                No staff assigned
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span class="segment-none">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:20px; font-size:11px; color:#6b7280;">
                        No shifts generated for this period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Note: Time bands (Morning / Afternoon / Night) are based on the start time of each shift.
    </div>

</body>

</html>