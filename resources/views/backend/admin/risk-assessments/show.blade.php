@extends('layouts.admin')
@section('title','Risk Assessment')
@section('content')

{{-- Header bar --}}
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">
    {{ $assessment->title }}
  </h1>
  <div class="space-x-2">
    <a href="{{ route('backend.admin.risk-assessments.edit', $assessment) }}"
       class="px-3 py-2 bg-amber-600 text-white rounded hover:bg-amber-700">Edit</a>
    <a href="{{ route('backend.admin.risk-assessments.index') }}"
       class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
  </div>
</div>

{{-- Summary card --}}
<div class="bg-white rounded-lg shadow p-4 space-y-3">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div><span class="font-medium">Service User:</span> {{ $assessment->serviceUser->full_name ?? ($assessment->serviceUser->first_name.' '.$assessment->serviceUser->last_name) }}</div>
    <div><span class="font-medium">Status:</span> {{ ucfirst($assessment->status) }}</div>
    <div><span class="font-medium">Start:</span> {{ $assessment->start_date? $assessment->start_date->format('d M Y') : '—' }}</div>
    <div><span class="font-medium">Next Review:</span> {{ $assessment->next_review_date? $assessment->next_review_date->format('d M Y') : '—' }}</div>
  </div>

  @if($assessment->summary)
    <div>
      <div class="font-medium mb-1">Summary</div>
      <div class="prose max-w-none">{!! nl2br(e($assessment->summary)) !!}</div>
    </div>
  @endif

  <div class="text-xs text-gray-500">
    Author: {{ $assessment->creator?->name ?? '—' }} on {{ $assessment->created_at->format('d M Y H:i') }}.
    @if($assessment->updated_at)
      &nbsp; Last Updated: {{ $assessment->updated_at->format('d M Y H:i') }}.
    @endif
  </div>

</div>

{{-- 2) Risk Types Accordion --}}
<div class="mt-4 bg-white rounded-lg shadow p-4">
  <h2 class="text-lg font-semibold mb-3">Risk Types</h2>

    {{-- 1) Add Risk Type button (admin can create a new type not yet in DB) --}}
    <form method="POST" action="{{ route('backend.admin.risk-types.store', $assessment) }}"
            class="bg-white rounded-lg shadow p-4 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-9 gap-4">
            <div class="md:col-span-3">
                <input name="name" placeholder="Risk Type" value="{{ old('name') }}" required
                        class="border rounded w-full px-3 py-2">
            </div>
            <div class="md:col-span-4">
                <input name="description" placeholder="Description (opt.)"
                            class="border rounded w-full px-3 py-2" type="text" value="{{ old('description') }}">
            </div>

            <div class="md:col-span-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">Add Risk Type</button>
            </div>
        </div>

    </form>


  @php
    // For quick lookup of items per type
    $itemsByType = $itemsByType ?? collect();
  @endphp

  <div class="divide-y">
    {{-- Loop through existing risk types --}}
    @forelse($riskTypes as $type)
      @php
        $list = $itemsByType->get($type->id, collect());
        $count = $list->count();
        $accordionId = 'rt_' . $type->id;
      @endphp

      <div class="py-2">
        <button type="button"
                class="w-full flex items-center justify-between text-left py-2"
                data-acc-target="#{{ $accordionId }}">
          <span class="font-medium">{{ $type->name }}</span>
          <span class="text-sm text-gray-500">({{ $count }})</span>
        </button>
        <div id="{{ $accordionId }}" class="hidden pt-2">
          @if($count === 0)
            <div class="text-sm text-gray-500 mb-2">No items under this risk type.</div>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="text-left p-3">Context</th>
                    <th class="text-left p-3">Likelihood</th>
                    <th class="text-left p-3">Severity</th>
                    <th class="text-left p-3">Score</th>
                    <th class="text-left p-3">Band</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-right p-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                  <tr class="border-t">
                    <td class="p-3">{{ $item->context ?? '—' }}</td>
                    <td class="p-3">{{ $item->likelihood }}</td>
                    <td class="p-3">{{ $item->severity }}</td>
                    <td class="p-3">{{ $item->risk_score }}</td>
                    <td class="p-3">{{ ucfirst($item->risk_band) }}</td>
                    <td class="p-3">{{ ucfirst($item->status) }}</td>
                    <td class="p-3 text-right space-x-2">
                      {{-- Wire later to item edit/delete routes --}}
                      <a href="#" class="px-2 py-1 text-amber-700 hover:underline">Edit</a>
                      <a href="#" class="px-2 py-1 text-red-700 hover:underline">Delete</a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          @endif

          {{-- Add Risk Item under this type (pass query ?risk_type_id=) --}}
          <div class="mt-3">
            <a href="{{ route('backend.admin.risk-items.create', ['profile' => $assessment->id, 'type' => $type->id]) }}"
                class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                + Add Item
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="text-sm text-gray-500">No risk types defined yet. Click "Add Risk Type" to create one.</div>
    @endforelse

    {{-- (Optional) Also show "Uncategorized" when items exist without a type --}}
    @php
      $uncat = $itemsByType->get(null, collect());
    @endphp
    @if($uncat->count() > 0)
      @php $accordionId = 'rt_uncategorized'; @endphp
      <div class="py-2">
        <button type="button"
                class="w-full flex items-center justify-between text-left py-2"
                data-acc-target="#{{ $accordionId }}">
          <span class="font-medium">Uncategorized</span>
          <span class="text-sm text-gray-500">({{ $uncat->count() }})</span>
        </button>
        <div id="{{ $accordionId }}" class="hidden pt-2">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="text-left p-3">Context</th>
                  <th class="text-left p-3">Likelihood</th>
                  <th class="text-left p-3">Severity</th>
                  <th class="text-left p-3">Score</th>
                  <th class="text-left p-3">Band</th>
                  <th class="text-left p-3">Status</th>
                </tr>
              </thead>
              <tbody>
              @foreach($uncat as $item)
                <tr class="border-t">
                  <td class="p-3">{{ $item->context ?? '—' }}</td>
                  <td class="p-3">{{ $item->likelihood }}</td>
                  <td class="p-3">{{ $item->severity }}</td>
                  <td class="p-3">{{ $item->risk_score }}</td>
                  <td class="p-3">{{ ucfirst($item->risk_band) }}</td>
                  <td class="p-3">{{ ucfirst($item->status) }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            @php $canAddItem = Route::has('backend.admin.risk-items.create'); @endphp
                @if($canAddItem)
                <a href="{{ route('backend.admin.risk-items.create', $assessment) }}?risk_type_id={{ $type->id }}"
                    class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add Risk Item
                </a>
                @endif
          </div>
        </div>
      </div>
    @endif
  </div>

</div>

  {{-- 3) Print button at lower-left of the card --}}
  <div class="flex items-center gap-2 mt-4">
    <a href="{{ route('backend.admin.risk-assessments.print', $assessment) }}" target="_blank"
       class="px-3 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Print / PDF</a>
  </div>


{{-- 4) Remaining sections – mirror care plan but guard with includeIf so nothing breaks yet --}}
@includeIf('backend.admin.risk-assessments.partials._reviews', ['assessment' => $assessment])
@includeIf('backend.admin.risk-assessments.partials._versions', ['assessment' => $assessment])
@includeIf('backend.admin.risk-assessments.partials._signoffs', ['assessment' => $assessment])

{{-- Lightweight accordion toggler (no external JS) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-acc-target]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var sel = this.getAttribute('data-acc-target');
      var el = document.querySelector(sel);
      if(!el) return;
      el.classList.toggle('hidden');
    });
  });
});
</script>
@endsection
