<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3"><h2 class="font-semibold">Reviews</h2></div>

  <form action="{{ route('backend.admin.risk-assessments.reviews.store', $risk_assessment) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-4">
    @csrf
    <input type="date" name="review_date" class="border rounded px-3 py-2">
    <input name="reason" placeholder="scheduled|incident|health_change" class="border rounded px-3 py-2">
    <input type="number" name="likelihood_new" min="1" max="5" placeholder="L (1-5)" class="border rounded px-3 py-2">
    <input type="number" name="severity_new" min="1" max="5" placeholder="S (1-5)" class="border rounded px-3 py-2">
    <input name="outcome" placeholder="continue|modify|archive" class="border rounded px-3 py-2">
    <button class="px-3 py-2 bg-amber-600 text-white rounded">Log Review</button>
    <div class="md:col-span-6"><textarea name="recommendations" rows="2" placeholder="Recommendations / notes..." class="border rounded w-full px-3 py-2"></textarea></div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50"><tr>
        <th class="text-left p-2">Date</th><th class="text-left p-2">Reason</th>
        <th class="text-left p-2">L→S</th><th class="text-left p-2">Score→Band</th><th class="text-left p-2">Outcome</th><th class="text-left p-2">By</th>
      </tr></thead>
      <tbody>
        @forelse($risk_assessment->reviews as $r)
          <tr class="border-t">
            <td class="p-2">{{ $r->review_date ?? $r->created_at->format('Y-m-d') }}</td>
            <td class="p-2">{{ $r->reason ?? '—' }}</td>
            <td class="p-2">{{ $r->likelihood_new ?? '—' }} × {{ $r->severity_new ?? '—' }}</td>
            <td class="p-2">{{ $r->score_new ?? '—' }} ({{ $r->band_new ? ucfirst($r->band_new) : '—' }})</td>
            <td class="p-2">{{ $r->outcome ?? '—' }}</td>
            <td class="p-2">{{ $r->reviewer?->name ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="6" class="p-3 text-center text-gray-500">No reviews yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
