@php
  // Simple helpers
  $su = $assessment->serviceUser ?? null;
  $serviceUserName = $su
    ? (method_exists($su, 'getFullNameAttribute') ? $su->full_name : trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? '')))
    : '—';

  function band($score)
  {
    if (!is_numeric($score))
      return '—';
    $s = (int) $score;
    if ($s >= 20)
      return 'Critical';
    if ($s >= 15)
      return 'High';
    if ($s >= 10)
      return 'Medium';
    return 'Low';
  }

  $fmtDate = fn($d) => $d ? $d->format('d M Y') : '—';
@endphp
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Risk Assessment — {{ $assessment->title }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
      font-size: 12px;
      color: #111;
      line-height: 1.6;
      padding: 20px;
      background: #f9fafb;
    }

    .print-container {
      max-width: 210mm;
      margin: 0 auto;
      background: white;
      padding: 20mm;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    @media print {
      body {
        background: white;
        padding: 0;
      }

      .print-container {
        box-shadow: none;
        padding: 15mm;
      }

      .no-print {
        display: none !important;
      }

      .page-break {
        page-break-after: always;
      }
    }

    @media print {
      @page {
        size: A4;
        margin: 15mm;
      }
    }

    h1 {
      font-size: 24px;
      font-weight: 700;
      color: #111;
      margin-bottom: 8px;
      border-bottom: 2px solid #dc2626;
      padding-bottom: 8px;
    }

    h2 {
      font-size: 18px;
      font-weight: 600;
      color: #1f2937;
      margin: 16px 0 8px 0;
      padding-bottom: 4px;
      border-bottom: 1px solid #e5e7eb;
    }

    h3 {
      font-size: 14px;
      font-weight: 600;
      color: #374151;
      margin: 12px 0 6px 0;
    }

    .muted {
      color: #6b7280;
      font-size: 11px;
    }

    .header-info {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 16px;
      padding: 12px;
      background: #f3f4f6;
      border-radius: 6px;
      font-size: 11px;
    }

    .header-info span {
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .section {
      page-break-inside: avoid;
      margin-bottom: 20px;
    }

    .box {
      border: 1px solid #d1d5db;
      border-radius: 6px;
      padding: 12px;
      margin: 8px 0;
      background: #f9fafb;
    }

    .risk-item-box {
      border-left: 3px solid #dc2626;
      background: #fef2f2;
      margin: 8px 0;
      padding: 10px;
    }

    .residual-box {
      border-left: 3px solid #3b82f6;
      background: #eff6ff;
      margin: 8px 0 0 20px;
      padding: 8px;
      font-size: 11px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 12px 0;
    }

    th,
    td {
      border: 1px solid #d1d5db;
      padding: 8px;
      vertical-align: top;
      text-align: left;
    }

    th {
      background: #f3f4f6;
      font-weight: 600;
      font-size: 11px;
    }

    td {
      font-size: 11px;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 10px;
      font-weight: 600;
      margin-right: 6px;
    }

    .badge-critical {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    .badge-high {
      background: #fed7aa;
      color: #92400e;
      border: 1px solid #fdba74;
    }

    .badge-medium {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fde68a;
    }

    .badge-low {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }

    .badge-status {
      background: #e0e7ff;
      color: #3730a3;
      border: 1px solid #c7d2fe;
    }

    .footer {
      margin-top: 24px;
      padding-top: 12px;
      border-top: 1px solid #e5e7eb;
      font-size: 10px;
      color: #6b7280;
      text-align: center;
    }

    .no-print {
      margin-bottom: 16px;
      padding: 12px;
      background: #1f2937;
      border-radius: 6px;
      text-align: center;
    }

    .no-print button {
      background: #dc2626;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    }

    .no-print button:hover {
      background: #b91c1c;
    }

    .score-cell {
      text-align: center;
      font-weight: 600;
    }

    .risk-details {
      margin-top: 8px;
      padding-top: 8px;
      border-top: 1px solid #e5e7eb;
    }

    .risk-details p {
      margin: 4px 0;
      font-size: 11px;
    }
  </style>
</head>

<body>
  <div class="no-print">
    <button onclick="window.print()">🖨️ Print / Save as PDF</button>
  </div>

  <div class="print-container">
    <h1>Risk Assessment: {{ $assessment->title }}</h1>

    <div class="header-info">
      <span><strong>Service User:</strong> {{ $serviceUserName }}</span>
      <span><strong>Status:</strong>
        <span class="badge badge-status">{{ ucfirst($assessment->status ?? 'draft') }}</span>
      </span>
      <span><strong>Start Date:</strong> {{ $fmtDate($assessment->start_date) }}</span>
      <span><strong>Next Review:</strong> {{ $fmtDate($assessment->next_review_date) }}</span>
      @if($assessment->review_frequency)
        <span><strong>Review Frequency:</strong> {{ $assessment->review_frequency }}</span>
      @endif
      @if($assessment->creator)
        <span><strong>Author:</strong> {{ $assessment->creator->name ?? '—' }}</span>
      @endif
      @if($assessment->created_at)
        <span><strong>Created:</strong> {{ $assessment->created_at->format('d M Y') }}</span>
      @endif
    </div>

    @if($assessment->summary)
      <div class="box">
        <h3>Summary</h3>
        <div style="margin-top: 6px; white-space: pre-line;">{!! nl2br(e($assessment->summary)) !!}</div>
      </div>
    @endif

    {{-- RISK ITEMS BY TYPE --}}
    @php $firstBlock = true; @endphp
    @foreach(($itemsByType ?? collect()) as $typeId => $items)
      @php
        $typeName = optional($items->first()->riskType)->name ?? 'Uncategorized';
      @endphp

      @if(!$firstBlock)
        <div class="page-break"></div>
      @endif
      @php $firstBlock = false; @endphp

      <div class="section">
        <h2>{{ $typeName }}</h2>

        @forelse($items as $item)
          @php
            $score = $item->risk_score ?? ($item->likelihood * $item->severity);
            $bandLabel = strtolower($item->risk_band ?? band($score));
            $bandClass = 'badge-' . $bandLabel;
          @endphp

          <div class="risk-item-box">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
              <div style="flex: 1;">
                <h3 style="margin: 0 0 4px 0;">{{ $item->hazard ?? $item->context ?? '—' }}</h3>
                @if($item->context && $item->hazard)
                  <p class="muted" style="margin: 0;">Context: {{ $item->context }}</p>
                @endif
              </div>
              <div style="text-align: right;">
                <span class="badge {{ $bandClass }}">{{ ucfirst($bandLabel) }}</span>
              </div>
            </div>

            <table style="margin: 8px 0;">
              <thead>
                <tr>
                  <th style="width: 15%;">Likelihood</th>
                  <th style="width: 15%;">Severity</th>
                  <th style="width: 15%;">Risk Score</th>
                  <th style="width: 15%;">Risk Band</th>
                  <th style="width: 40%;">Controls / Mitigations</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="score-cell">{{ $item->likelihood ?? '—' }}</td>
                  <td class="score-cell">{{ $item->severity ?? '—' }}</td>
                  <td class="score-cell"><strong>{{ $score ?? '—' }}</strong></td>
                  <td class="score-cell">
                    <span class="badge {{ $bandClass }}">{{ ucfirst($bandLabel) }}</span>
                  </td>
                  <td>{!! nl2br(e($item->controls ?? '—')) !!}</td>
                </tr>
              </tbody>
            </table>

            @if(!empty($item->residual_likelihood) || !empty($item->residual_severity))
              @php
                $rScore = $item->residual_score ?? (
                  (int) ($item->residual_likelihood ?? 0) > 0 && (int) ($item->residual_severity ?? 0) > 0
                  ? ($item->residual_likelihood * $item->residual_severity)
                  : null
                );
                $rBandLabel = strtolower(band($rScore));
                $rBandClass = 'badge-' . $rBandLabel;
              @endphp
              <div class="residual-box">
                <strong>Residual Risk (After Controls):</strong>
                <table style="margin: 6px 0 0 0;">
                  <thead>
                    <tr>
                      <th style="width: 15%;">Residual Likelihood</th>
                      <th style="width: 15%;">Residual Severity</th>
                      <th style="width: 15%;">Residual Score</th>
                      <th style="width: 15%;">Residual Band</th>
                      <th style="width: 40%;">Notes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="score-cell">{{ $item->residual_likelihood ?? '—' }}</td>
                      <td class="score-cell">{{ $item->residual_severity ?? '—' }}</td>
                      <td class="score-cell"><strong>{{ $rScore ?? '—' }}</strong></td>
                      <td class="score-cell">
                        @if($rScore)
                          <span class="badge {{ $rBandClass }}">{{ ucfirst($rBandLabel) }}</span>
                        @else
                          —
                        @endif
                      </td>
                      <td>Risk level after implementing controls</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            @endif

            @if(isset($item->owner_id) && $item->owner_id && $item->owner)
              <div class="risk-details">
                <p><strong>Risk Owner:</strong> {{ $item->owner->first_name ?? '—' }}</p>
              </div>
            @endif

            @if($item->next_review_date)
              <div class="risk-details">
                <p><strong>Next Review Date:</strong> {{ $fmtDate($item->next_review_date) }}</p>
              </div>
            @endif
          </div>
        @empty
          <div class="box" style="text-align: center; color: #6b7280;">
            No risk items recorded under this type.
          </div>
        @endforelse
      </div>
    @endforeach

    @if(($itemsByType ?? collect())->isEmpty())
      <div class="box" style="text-align: center; color: #6b7280;">
        No risk items have been recorded for this assessment.
      </div>
    @endif

    <div class="footer">
      <div>Generated: {{ now('Europe/London')->format('d M Y H:i') }}</div>
      <div style="margin-top: 4px;">
        Author: {{ $assessment->creator?->name ?? '—' }}
        @if($assessment->created_at)
          • Created: {{ $assessment->created_at->format('d M Y H:i') }}
        @endif
        @if($assessment->updated_at)
          • Last Updated: {{ $assessment->updated_at->format('d M Y H:i') }}
        @endif
      </div>
      <div style="margin-top: 4px;">
        Printed by: {{ auth()->user()->name ?? 'System' }}
      </div>
    </div>
  </div>

</body>

</html>