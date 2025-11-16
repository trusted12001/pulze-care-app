<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Care Plan — {{ $care_plan->serviceUser->full_name ?? 'Unknown' }}</title>
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

    h1 {
      font-size: 24px;
      font-weight: 700;
      color: #111;
      margin-bottom: 8px;
      border-bottom: 2px solid #3b82f6;
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

    .goal-box {
      border-left: 3px solid #3b82f6;
      background: #eff6ff;
      margin: 8px 0;
    }

    .intervention-list {
      margin-top: 8px;
      padding-left: 20px;
    }

    .intervention-list li {
      margin: 4px 0;
      font-size: 11px;
    }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 10px;
      font-weight: 500;
      margin-right: 6px;
    }

    .badge-primary {
      background: #dbeafe;
      color: #1e40af;
    }

    .badge-success {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-warning {
      background: #fef3c7;
      color: #92400e;
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
      background: #3b82f6;
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
      background: #2563eb;
    }

    @media print {
      @page {
        size: A4;
        margin: 15mm;
      }
    }
  </style>
</head>

<body>
  <div class="no-print">
    <button onclick="window.print()">🖨️ Print / Save as PDF</button>
  </div>

  <div class="print-container">
    <h1>Care Plan: {{ $care_plan->title }}
      <span class="muted">(v{{ $care_plan->version }})</span>
    </h1>

    <div class="header-info">
      <span><strong>Service User:</strong> {{ $care_plan->serviceUser->full_name ?? '—' }}</span>
      <span><strong>Status:</strong>
        <span
          class="badge {{ $care_plan->status === 'active' ? 'badge-success' : ($care_plan->status === 'draft' ? 'badge-warning' : '') }}">
          {{ ucfirst($care_plan->status) }}
        </span>
      </span>
      <span><strong>Start:</strong> {{ $care_plan->start_date ? $care_plan->start_date->format('d M Y') : '—' }}</span>
      <span><strong>Next Review:</strong>
        {{ $care_plan->next_review_date ? $care_plan->next_review_date->format('d M Y') : '—' }}</span>
      @if($care_plan->review_frequency)
        <span><strong>Review Frequency:</strong> {{ $care_plan->review_frequency }}</span>
      @endif
    </div>

    @if($care_plan->summary)
      <div class="box">
        <h3>Summary</h3>
        <div style="margin-top: 6px; white-space: pre-line;">{!! nl2br(e($care_plan->summary)) !!}</div>
      </div>
    @endif

    @forelse($care_plan->sections as $sec)
      <div class="section">
        <h2>{{ $sec->name }}</h2>
        @if($sec->description)
          <div class="muted" style="margin-bottom: 12px;">{{ $sec->description }}</div>
        @endif

        @forelse($sec->goals as $g)
          <div class="box goal-box">
            <h3>Goal: {{ $g->title }}</h3>
            @if($g->target_date)
              <div class="muted" style="margin-top: 4px;">
                <strong>Target Date:</strong> {{ $g->target_date->format('d M Y') }}
              </div>
            @endif
            @if($g->success_criteria)
              <div class="muted" style="margin-top: 4px;">
                <strong>Success Criteria:</strong> {{ $g->success_criteria }}
              </div>
            @endif
            @if($g->status)
              <div style="margin-top: 4px;">
                <span class="badge badge-primary">Status: {{ ucfirst($g->status) }}</span>
              </div>
            @endif

            @if($g->interventions->count())
              <div style="margin-top: 12px;">
                <strong style="font-size: 12px;">Interventions:</strong>
                <ul class="intervention-list">
                  @foreach($g->interventions as $iv)
                    <li>
                      {{ $iv->description }}
                      @if($iv->frequency) <span class="muted">• {{ $iv->frequency }}</span> @endif
                      @if($iv->assigned_to_role) <span class="muted">• {{ $iv->assigned_to_role }}</span> @endif
                      @if($iv->assignee) <span class="muted">• {{ $iv->assignee->name }}</span> @endif
                      @if($iv->link_to_assignment) <span class="muted">• Linked to assignment</span> @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        @empty
          <div class="muted" style="padding: 8px;">No goals defined for this section.</div>
        @endforelse
      </div>
    @empty
      <div class="box" style="text-align: center; color: #6b7280;">
        No sections defined in this care plan.
      </div>
    @endforelse

    <div class="footer">
      <div>Generated: {{ now()->format('d M Y H:i') }}</div>
      <div style="margin-top: 4px;">
        Author: {{ $care_plan->author?->name ?? '—' }}
        @if($care_plan->approved_at)
          • Approved by: {{ $care_plan->approver?->name ?? '—' }} ({{ $care_plan->approved_at->format('d M Y H:i') }})
        @endif
      </div>
    </div>
  </div>

</body>

</html>