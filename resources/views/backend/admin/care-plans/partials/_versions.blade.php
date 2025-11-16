<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
  <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-slate-50 to-gray-50 border-b border-gray-200">
    <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
      <i class="ph ph-clock-counter-clockwise text-slate-600 flex-shrink-0"></i>
      <span>Version History</span>
    </h2>
  </div>

  <div class="p-4 sm:p-5 lg:p-6">
    @forelse($care_plan->versions as $v)
      <div class="mb-3 last:mb-0 p-4 bg-gray-50 border border-gray-200 rounded-lg">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-3">
          <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
              <span
                class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-800 rounded text-xs sm:text-sm font-semibold">
                <i class="ph ph-file-text"></i>
                Version {{ $v->version }}
              </span>
              @if($v->approved_at)
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                  <i class="ph ph-check-circle"></i>
                  Approved
                </span>
              @endif
            </div>
            @if($v->change_note)
              <p class="text-sm text-gray-700 mb-2">{{ $v->change_note }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
              @if($v->approved_at)
                <span class="flex items-center gap-1">
                  <i class="ph ph-calendar"></i>
                  {{ $v->approved_at->format('d M Y, h:i A') }}
                </span>
              @endif
              @if($v->approver)
                <span class="flex items-center gap-1">
                  <i class="ph ph-user"></i>
                  {{ $v->approver->name ?? '—' }}
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-6 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
        <i class="ph ph-clock text-gray-400 text-2xl mb-2"></i>
        <p>No prior versions.</p>
      </div>
    @endforelse
  </div>
</div>