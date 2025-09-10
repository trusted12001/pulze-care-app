@php
  $isEdit = isset($record) && $record->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Module Code</label>
    <input type="text" name="module_code" value="{{ old('module_code', $record->module_code ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('module_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Module Title</label>
    <input type="text" name="module_title" value="{{ old('module_title', $record->module_title ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('module_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Provider</label>
    <input type="text" name="provider" value="{{ old('provider', $record->provider ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('provider') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Completed At</label>
    <input type="date" name="completed_at"
           value="{{ old('completed_at', optional($record->completed_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('completed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Valid Until</label>
    <input type="date" name="valid_until"
           value="{{ old('valid_until', optional($record->valid_until ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('valid_until') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Score (0–100)</label>
    <input type="number" name="score" min="0" max="100"
           value="{{ old('score', $record->score ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('score') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Evidence File ID</label>
    <input type="number" name="evidence_file_id"
           value="{{ old('evidence_file_id', $record->evidence_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('evidence_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.training-records.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
