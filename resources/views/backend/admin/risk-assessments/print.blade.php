@php
  // Simple helpers in case accessors aren't present
  $su = $assessment->serviceUser ?? null;
  $serviceUserName = $su
    ? (method_exists($su,'getFullNameAttribute') ? $su->full_name : trim(($su->first_name ?? '').' '.($su->last_name ?? '')))
    : '—';

  function band($score) {
      if (!is_numeric($score)) return '—';
      $s = (int)$score;
      return $s >= 15 ? 'High' : ($s >= 8 ? 'Medium' : 'Low');
  }
@endphp
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Risk Assessment #{{ $assessment->id }}</title>
  <style>
    /* A4 print styles for DomPDF */
    @page { margin: 24mm 18mm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    h1,h2,h3 { margin: 0 0 6px; }
    .muted { color: #666; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 16px; }
    .section { margin-top: 18px; }
    .chip { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; border: 1px solid #ccc; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; }
    .box { border: 1px solid #ddd; border-radius: 6px; padding: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; vertical-align: top; }
    th { background: #f5f5f5; text-align: left; }
    .right { text-align: right; }
    .small { font-size: 11px; }
    .page-break { page-break-after: always; }
    footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: center; font-size: 10px; color: #666; }
  </style>
</head>
<body>

  {{-- HEADER --}}
  <h1>Risk Assessment</h1>
  <div class="muted mb-3">ID: {{ $assessment->id }}</div>

  {{-- SUMMARY / META --}}
  <div class="box mb-4">
    <div class="grid-2">
      <div><strong>Service User:</strong> {{ $serviceUserName }}</div>
      <div><strong>Status:</strong> {{ ucfirst($assessment->status ?? '—') }}</div>

      <div><strong>Start Date:</strong> {{ optional($assessment->start_date)->format('d M Y') ?? '—' }}</div>
      <div><strong>Next Review:</strong> {{ optional($assessment->next_review_date)->format('d M Y') ?? '—' }}</div>

      <div><strong>Review Frequency:</strong> {{ $assessment->review_frequency ?? '—' }}</div>
      <div><strong>Author:</strong> {{ $assessment->creator?->name ?? '—' }}</div>
    </div>

    @if($assessment->summary)
      <div class="section">
        <strong>Summary</strong>
        <div class="small" style="margin-top:4px;">{!! nl2br(e($assessment->summary)) !!}</div>
      </div>
    @endif
  </div>

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

    <h2 style="margin-bottom:6px;">{{ $typeName }}</h2>
    <table>
      <thead>
        <tr>
          <th style="width:28%;">Context</th>
          <th style="width:10%;">Likelihood</th>
          <th style="width:10%;">Severity</th>
          <th style="width:10%;">Score</th>
          <th style="width:12%;">Band</th>
          <th style="width:30%;">Controls / Mitigations</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $item)
        @php
          // allow both accessors and raw columns
          $score = $item->risk_score ?? ($item->score ?? ($item->likelihood * $item->severity));
          $bandLabel = $item->risk_band ?? band($score);
        @endphp
        <tr>
          <td>{{ $item->context ?? $item->hazard ?? '—' }}</td>
          <td>{{ $item->likelihood ?? '—' }}</td>
          <td>{{ $item->severity ?? '—' }}</td>
          <td>{{ $score ?? '—' }}</td>
          <td>{{ ucfirst($bandLabel) }}</td>
          <td class="small">{!! nl2br(e($item->controls ?? '—')) !!}</td>
        </tr>
        @if(!empty($item->residual_likelihood) || !empty($item->residual_severity))
          @php
            $rScore = $item->residual_score ?? (
              (int)($item->residual_likelihood) > 0 && (int)($item->residual_severity) > 0
                ? $item->residual_likelihood * $item->residual_severity
                : null
            );
          @endphp
          <tr>
            <td class="small muted">Residual</td>
            <td class="small">{{ $item->residual_likelihood ?? '—' }}</td>
            <td class="small">{{ $item->residual_severity ?? '—' }}</td>
            <td class="small">{{ $rScore ?? '—' }}</td>
            <td class="small">{{ $rScore ? band($rScore) : '—' }}</td>
            <td class="small muted">— after controls</td>
          </tr>
        @endif
      @empty
        <tr><td colspan="6" class="small muted">No items recorded under this type.</td></tr>
      @endforelse
      </tbody>
    </table>
  @endforeach

  {{-- FOOTER --}}
  <footer>
    Printed on {{ now('Europe/London')->format('d M Y H:i') }} by {{ auth()->user()->name ?? 'System' }}
  </footer>
</body>
</html>
