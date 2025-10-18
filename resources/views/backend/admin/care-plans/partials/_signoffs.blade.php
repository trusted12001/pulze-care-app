<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold">Digital Sign-offs</h2>
  </div>

  <form action="{{ route('backend.admin.care-plans.signoffs.store', $care_plan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
    @csrf
    <input name="role_label" placeholder="Role e.g. Key Worker" class="border rounded px-3 py-2">
    <input name="pin_last4" placeholder="PIN last 4 (optional)" maxlength="4" class="border rounded px-3 py-2">
    <div class="md:col-span-2">
      <button class="px-3 py-2 bg-green-600 text-white rounded">Sign Off Current Version</button>
    </div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-2">User</th>
          <th class="text-left p-2">Role</th>
          <th class="text-left p-2">Version</th>
          <th class="text-left p-2">Signed At</th>
        </tr>
      </thead>
      <tbody>
        @forelse($care_plan->signoffs as $s)
        <tr class="border-t">
          <td class="p-2">{{ $s->user?->first_name ?? '—' }}</td>
          <td class="p-2">{{ $s->role_label ?? '—' }}</td>
          <td class="p-2">v{{ $s->version_at_sign }}</td>
          <td class="p-2">{{ $s->signed_at? $s->signed_at->format('d M Y H:i') : '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="p-3 text-center text-gray-500">No sign-offs yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
