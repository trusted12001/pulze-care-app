@php
  $isEdit = isset($record) && $record->exists;
  $types = ['annual'=>'Annual','sick'=>'Sick','unpaid'=>'Unpaid','maternity'=>'Maternity','paternity'=>'Paternity','compassionate'=>'Compassionate','study'=>'Study','other'=>'Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Type</label>
    @php $t = old('type', $record->type ?? 'annual'); @endphp
    <select name="type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($types as $v=>$l) <option value="{{ $v }}" @selected($t===$v)>{{ $l }}</option> @endforeach
    </select>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Hours (optional)</label>
    <input type="number" step="0.01" name="hours"
           value="{{ old('hours', $record->hours ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('hours') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Start Date</label>
    <input type="date" name="start_date"
           value="{{ old('start_date', optional($record->start_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">End Date</label>
    <input type="date" name="end_date"
           value="{{ old('end_date', optional($record->end_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Reason (optional)</label>
    <textarea name="reason" rows="3"
              class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('reason', $record->reason ?? '') }}</textarea>
    @error('reason') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Fit Note File ID (optional)</label>
    <input type="number" name="fit_note_file_id"
           value="{{ old('fit_note_file_id', $record->fit_note_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('fit_note_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.leave-records.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
