<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold">Controls</h2>
  </div>

  <form action="{{ route('backend.admin.risk-assessments.controls.store', $risk_assessment) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-4">
    @csrf
    <input name="control_type" placeholder="Type (e.g., admin)" class="border rounded px-3 py-2">
    <input name="frequency" placeholder="each_shift|daily|weekly" class="border rounded px-3 py-2">
    <input name="assigned_to_role" placeholder="role (optional)" class="border rounded px-3 py-2">
    <input type="number" name="assigned_to_user_id" placeholder="user id (optional)" class="border rounded px-3 py-2">
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="mandatory" value="1"> <span>Mandatory</span></label>
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="link_to_assignment" value="1"> <span>Link to Assignment</span></label>
    <div class="md:col-span-6">
      <textarea name="description" rows="2" placeholder="Describe the control..." class="border rounded w-full px-3 py-2" required></textarea>
    </div>
    <div class="md:col-span-6"><button class="px-3 py-2 bg-blue-600 text-white rounded">Add Control</button></div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50"><tr>
        <th class="text-left p-2">Type</th><th class="text-left p-2">Desc</th><th class="text-left p-2">Freq</th>
        <th class="text-left p-2">Mandatory</th><th class="text-left p-2">Assignee</th><th class="text-left p-2">Linked</th><th class="text-right p-2">Actions</th>
      </tr></thead>
      <tbody>
        @forelse($risk_assessment->controls as $c)
        <tr class="border-t">
          <td class="p-2">{{ $c->control_type ?? '—' }}</td>
          <td class="p-2">{{ $c->description }}</td>
          <td class="p-2">{{ $c->frequency ?? '—' }}</td>
          <td class="p-2">{{ $c->mandatory ? 'Yes' : 'No' }}</td>
          <td class="p-2">{{ $c->assigned_to_role ?? ($c->assignee?->name ?? '—') }}</td>
          <td class="p-2">{{ $c->link_to_assignment ? 'Yes' : 'No' }}</td>
          <td class="p-2 text-right">
            <form action="{{ route('backend.admin.controls.destroy', $c) }}" method="POST" onsubmit="return confirm('Remove control?')">
              @csrf @method('DELETE')
              <button class="text-red-600 hover:underline">Delete</button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="p-3 text-center text-gray-500">No controls yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
