@php
  $isEdit = isset($record) && $record->exists;
  $types = ['informal'=>'Informal','formal'=>'Formal','warning'=>'Warning','dismissal'=>'Dismissal'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Type</label>
    @php $t = old('type', $record->type ?? 'informal'); @endphp
    <select name="type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($types as $v=>$l) <option value="{{ $v }}" @selected($t===$v)>{{ $l }}</option> @endforeach
    </select>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Opened At</label>
    <input type="date" name="opened_at"
           value="{{ old('opened_at', optional($record->opened_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('opened_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Closed At (optional)</label>
    <input type="date" name="closed_at"
           value="{{ old('closed_at', optional($record->closed_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('closed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Summary</label>
    <textarea name="summary" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>{{ old('summary', $record->summary ?? '') }}</textarea>
    @error('summary') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Outcome (optional)</label>
    <textarea name="outcome" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('outcome', $record->outcome ?? '') }}</textarea>
    @error('outcome') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.disciplinary.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
