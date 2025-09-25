<div class="mt-6 bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-semibold">Sections (Domains)</h2>
  </div>

  {{-- Add Section --}}
  <form action="{{ route('backend.admin.care-plans.sections.store', $plan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
    @csrf
    <input name="name" placeholder="Section name..." class="border rounded px-3 py-2" required>
    <input name="description" placeholder="Description (optional)" class="border rounded px-3 py-2 md:col-span-2">
    <button class="px-3 py-2 bg-blue-600 text-white rounded">Add Section</button>
  </form>

  @forelse($plan->sections as $section)
    <div class="border rounded mb-4">
      <div class="flex items-center justify-between p-3 bg-gray-50">
        <div class="font-medium">{{ $section->name }}</div>
        <form action="{{ route('backend.admin.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Remove this section?')">
          @csrf @method('DELETE')
          <button class="text-red-600 hover:underline">Delete</button>
        </form>
      </div>

      <div class="p-3 space-y-3">
        @if($section->description)
          <div class="text-sm text-gray-700">{{ $section->description }}</div>
        @endif

        {{-- Add Goal --}}
        <form action="{{ route('backend.admin.sections.goals.store', $section) }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
          @csrf
          <input name="title" placeholder="Goal title..." class="border rounded px-3 py-2 md:col-span-2" required>
          <input type="date" name="target_date" class="border rounded px-3 py-2">
          <input name="success_criteria" placeholder="Success criteria (optional)" class="border rounded px-3 py-2 md:col-span-2">
          <div class="md:col-span-5">
            <button class="px-3 py-2 bg-green-600 text-white rounded">Add Goal</button>
          </div>
        </form>

        {{-- Goals list --}}
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left p-2">Goal</th>
                <th class="text-left p-2">Target</th>
                <th class="text-left p-2">Status</th>
                <th class="text-left p-2">Interventions</th>
                <th class="text-right p-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($section->goals as $goal)
              <tr class="border-t align-top">
                <td class="p-2">
                  <div class="font-medium">{{ $goal->title }}</div>
                  @if($goal->success_criteria)
                    <div class="text-xs text-gray-600">Success: {{ $goal->success_criteria }}</div>
                  @endif
                </td>
                <td class="p-2">{{ $goal->target_date? $goal->target_date->format('d M Y') : '—' }}</td>
                <td class="p-2">{{ ucfirst($goal->status) }}</td>
                <td class="p-2 w-1/2">
                  {{-- Add Intervention --}}
                  <form action="{{ route('backend.admin.goals.interventions.store', $goal) }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-2">
                    @csrf
                    <input name="description" placeholder="Intervention..." class="border rounded px-3 py-2 md:col-span-2" required>
                    <input name="frequency" placeholder="each_shift|daily|weekly" class="border rounded px-3 py-2">
                    <input name="assigned_to_role" placeholder="role (opt.)" class="border rounded px-3 py-2">
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" name="link_to_assignment" value="1"> <span class="text-xs">Link to Assignment</span>
                    </label>
                    <div class="md:col-span-5"></div>
                  </form>

                  {{-- List interventions --}}
                  <ul class="list-disc list-inside space-y-1">
                    @forelse($goal->interventions as $iv)
                      <li>
                        <span class="font-medium">{{ $iv->description }}</span>
                        <span class="text-xs text-gray-600">
                          @if($iv->frequency) • {{ $iv->frequency }} @endif
                          @if($iv->assigned_to_role) • {{ $iv->assigned_to_role }} @endif
                          @if($iv->assignee) • {{ $iv->assignee->name }} @endif
                          @if($iv->link_to_assignment) • linked to assignment @endif
                        </span>
                        <form action="{{ route('backend.admin.interventions.destroy', $iv) }}" method="POST" class="inline" onsubmit="return confirm('Remove intervention?')">
                          @csrf @method('DELETE')
                          <button class="text-red-600 hover:underline text-xs ml-2">Delete</button>
                        </form>
                      </li>
                    @empty
                      <li class="text-gray-500">No interventions yet.</li>
                    @endforelse
                  </ul>
                </td>
                <td class="p-2 text-right">
                  <form action="{{ route('backend.admin.goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Remove goal?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="p-3 text-center text-gray-500">No goals yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center text-gray-500">No sections yet.</div>
  @endforelse
</div>
