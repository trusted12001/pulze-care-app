<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold">Reviews</h2>
  </div>

  <form action="{{ route('backend.admin.care-plans.reviews.store', $care_plan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-4">
    @csrf
    <input type="date" name="review_date" class="border rounded px-3 py-2">
    <input type="text" name="reason" placeholder="scheduled|change_in_needs|incident" class="border rounded px-3 py-2">
    <input type="text" name="review_frequency_suggested" placeholder="e.g. 24 weeks" class="border rounded px-3 py-2">
    <input type="date" name="next_review_date_suggested" class="border rounded px-3 py-2">
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="bump_version" value="1">
      <span>Bump Version</span>
    </label>
    <input type="text" name="change_note" placeholder="Change note (optional)" class="border rounded px-3 py-2">
    <div class="md:col-span-6">
      <textarea name="summary" rows="2" placeholder="Summary / notes..." class="border rounded w-full px-3 py-2"></textarea>
    </div>
    <div class="md:col-span-6"><button class="px-3 py-2 bg-amber-600 text-white rounded">Log Review</button></div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-2">Date</th>
          <th class="text-left p-2">Reason</th>
          <th class="text-left p-2">Summary</th>
          <th class="text-left p-2">Next Review (suggested)</th>
          <th class="text-left p-2">By</th>
        </tr>
      </thead>
      <tbody>
        @forelse($care_plan->reviews as $r)
        <tr class="border-t">
          <td class="p-2">{{ $r->review_date ?? $r->created_at->format('Y-m-d') }}</td>
          <td class="p-2">{{ $r->reason ?? '—' }}</td>
          <td class="p-2">{{ $r->summary ?? '—' }}</td>
          <td class="p-2">
            {{ $r->next_review_date_suggested? $r->next_review_date_suggested->format('d M Y') : '—' }}
            @if($r->review_frequency_suggested) ({{ $r->review_frequency_suggested }}) @endif
          </td>
          <td class="p-2">{{ $r->reviewer?->name ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="p-3 text-center text-gray-500">No reviews yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
