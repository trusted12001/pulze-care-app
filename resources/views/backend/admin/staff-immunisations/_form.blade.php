@php
  $isEdit = isset($immunisation) && $immunisation->exists;
  $vaccines = $vaccines ?? ['HepB','MMR','Varicella','TB_BCG','Flu','Covid19','Tetanus','Pertussis','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Vaccine</label>
    @php $v = old('vaccine', $immunisation->vaccine ?? 'HepB'); @endphp
    <select name="vaccine" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($vaccines as $vx)
        <option value="{{ $vx }}" @selected($v === $vx)>{{ $vx }}</option>
      @endforeach
    </select>
    @error('vaccine') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Dose</label>
    <input type="text" name="dose" value="{{ old('dose', $immunisation->dose ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., 1st dose, booster">
    @error('dose') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Administered At</label>
    <input type="date" name="administered_at"
           value="{{ old('administered_at', optional($immunisation->administered_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('administered_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Evidence File ID</label>
    <input type="number" name="evidence_file_id"
           value="{{ old('evidence_file_id', $immunisation->evidence_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="(optional)">
    @error('evidence_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $immunisation->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.immunisations.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
