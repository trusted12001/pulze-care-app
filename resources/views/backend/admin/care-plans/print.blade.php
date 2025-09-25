<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Care Plan — {{ $care_plan->serviceUser->full_name }}</title>
<style>
  body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; font-size: 12px; color: #111; }
  h1,h2,h3 { margin: 0 0 6px; }
  .muted { color:#555; }
  .section { page-break-inside: avoid; margin-bottom: 12px; }
  .box { border:1px solid #ddd; border-radius:8px; padding:10px; margin:6px 0; }
  @media print { .no-print { display:none; } }
</style>
</head>
<body>
<div class="no-print" style="margin-bottom:8px;">
  <button onclick="window.print()">Print / Save as PDF</button>
</div>

<h1>Care Plan: {{ $care_plan->title }} <span class="muted"> (v{{ $care_plan->version }})</span></h1>
<div class="muted">
  Service User: {{ $care_plan->serviceUser->full_name }} |
  Status: {{ ucfirst($care_plan->status) }} |
  Start: {{ $care_plan->start_date? $care_plan->start_date->format('d M Y') : '—' }} |
  Next review: {{ $care_plan->next_review_date? $care_plan->next_review_date->format('d M Y') : '—' }}
</div>

@if($care_plan->summary)
  <div class="box"><strong>Summary:</strong><br>{!! nl2br(e($care_plan->summary)) !!}</div>
@endif

@foreach($care_plan->sections as $sec)
  <div class="section">
    <h2>{{ $sec->name }}</h2>
    @if($sec->description)
      <div class="muted">{{ $sec->description }}</div>
    @endif

    @foreach($sec->goals as $g)
      <div class="box">
        <strong>Goal:</strong> {{ $g->title }}
        @if($g->target_date) <span class="muted"> (Target: {{ $g->target_date->format('d M Y') }})</span> @endif
        @if($g->success_criteria) <div class="muted">Success: {{ $g->success_criteria }}</div> @endif

        @if($g->interventions->count())
          <div style="margin-top:6px;"><strong>Interventions:</strong>
            <ul>
              @foreach($g->interventions as $iv)
                <li>
                  {{ $iv->description }}
                  @if($iv->frequency) <span class="muted"> • {{ $iv->frequency }}</span> @endif
                  @if($iv->assigned_to_role) <span class="muted"> • {{ $iv->assigned_to_role }}</span> @endif
                  @if($iv->assignee) <span class="muted"> • {{ $iv->assignee->name }}</span> @endif
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    @endforeach
  </div>
@endforeach

<div class="muted" style="margin-top:12px;">
  Generated: {{ now()->format('d M Y H:i') }} • Author: {{ $care_plan->author?->name }} •
  @if($care_plan->approved_at) Approved by: {{ $care_plan->approver?->name }} ({{ $care_plan->approved_at->format('d M Y H:i') }}) @endif
</div>
</body>
</html>
