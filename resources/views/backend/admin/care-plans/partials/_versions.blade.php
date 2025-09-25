<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold">Version History</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-2">Version</th>
          <th class="text-left p-2">Approved At</th>
          <th class="text-left p-2">Approved By</th>
          <th class="text-left p-2">Change Note</th>
        </tr>
      </thead>
      <tbody>
        @forelse($care_plan->versions as $v)
        <tr class="border-t">
          <td class="p-2">v{{ $v->version }}</td>
          <td class="p-2">{{ $v->approved_at? $v->approved_at->format('d M Y H:i') : '—' }}</td>
          <td class="p-2">{{ $v->approver?->name ?? '—' }}</td>
          <td class="p-2">{{ $v->change_note ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="p-3 text-center text-gray-500">No prior versions.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
