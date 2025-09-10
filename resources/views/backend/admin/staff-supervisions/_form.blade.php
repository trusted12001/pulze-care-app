@php
  $isEdit = isset($supervision) && $supervision->exists;
  $types = ['supervision' => 'Supervision', 'appraisal' => 'Appraisal', 'probation_review' => 'Probation Review'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Type</label>
    @php $tp = old('type', $supervision->type ?? 'supervision'); @endphp
    <select name="type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($types as $v => $label)
        <option value="{{ $v }}" @selected($tp===$v)>{{ $label }}</option>
      @endforeach
    </select>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Held At</label>
    <input type="date" name="held_at"
           value="{{ old('held_at', optional($supervision->held_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('held_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Manager</label>
    <select name="manager_user_id" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">— Select —</option>
      @foreach(($managers ?? collect()) as $m)
        @php $mname = $m->full_name ?? trim("{$m->first_name} {$m->other_names} {$m->last_name}"); @endphp
        <option value="{{ $m->id }}" @selected(old('manager_user_id', $supervision->manager_user_id ?? null)===$m->id)>
          {{ $mname }} — {{ $m->email }}
        </option>
      @endforeach
    </select>
    @error('manager_user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Next Due</label>
    <input type="date" name="next_due_at"
           value="{{ old('next_due_at', optional($supervision->next_due_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('next_due_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Summary</label>
    <textarea name="summary" rows="5"
              class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
              required>{{ old('summary', $supervision->summary ?? '') }}</textarea>
    @error('summary') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.supervisions.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
