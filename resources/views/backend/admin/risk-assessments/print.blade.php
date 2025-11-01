<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Risk Assessment – {{ $assessment->serviceUser->full_name ?? '' }}</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    h1,h2,h3 { margin: 0 0 8px; }
    .muted { color: #666; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; }
    th { background: #f7f7f7; text-align: left; }
  </style>
</head>
<body>
  <h1>Risk Assessment</h1>
  <p class="muted">
    Service User: <strong>{{ $assessment->serviceUser->full_name ?? ($assessment->serviceUser->first_name.' '.$assessment->serviceUser->last_name) }}</strong><br>
    Title: <strong>{{ $assessment->title }}</strong><br>
    Status: {{ ucfirst($assessment->status) }}<br>
    Start: {{ $assessment->start_date? $assessment->start_date->format('d M Y') : '—' }} |
    Next Review: {{ $assessment->next_review_date? $assessment->next_review_date->format('d M Y') : '—' }}<br>
    Generated: {{ now()->format('d M Y H:i') }}
  </p>

  @if($assessment->summary)
    <h3>Summary</h3>
    <p>{!! nl2br(e($assessment->summary)) !!}</p>
  @endif

  <h3>Risk Items</h3>
  <table>
    <thead>
      <tr>
        <th>Risk Type</th>
        <th>Context</th>
        <th>L</th>
        <th>S</th>
        <th>Score</th>
        <th>Band</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($assessment->assessments as $item)
        <tr>
          <td>{{ $item->riskType?->name ?? '—' }}</td>
          <td>{{ $item->context ?? '—' }}</td>
          <td>{{ $item->likelihood }}</td>
          <td>{{ $item->severity }}</td>
          <td>{{ $item->risk_score }}</td>
          <td>{{ ucfirst($item->risk_band) }}</td>
          <td>{{ ucfirst($item->status) }}</td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted">No items recorded.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
