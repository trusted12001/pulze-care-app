<div class="bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h3 class="font-semibold">Top Risks — {{ $service_user->full_name }}</h3>
  </div>

  <ul class="divide-y">
    @forelse($risks as $r)
      <li class="py-2">
        <div class="flex items-center justify-between">
          <div>
            <div class="font-medium">{{ $r->title }} <span class="text-xs text-gray-500">({{ $r->riskType?->name }})</span></div>
            <div class="text-xs text-gray-600">Score: {{ $r->risk_score }} • {{ ucfirst($r->risk_band) }}</div>
            @if($r->context)
              <div class="text-xs text-gray-500 mt-1">What to do: {{ \Illuminate\Support\Str::limit($r->context, 140) }}</div>
            @endif
          </div>
          @include('backend.admin.risk-assessments.partials.band-badge',['band'=>$r->risk_band])
        </div>
      </li>
    @empty
      <li class="py-2 text-gray-500">No active risks.</li>
    @endforelse
  </ul>
</div>
