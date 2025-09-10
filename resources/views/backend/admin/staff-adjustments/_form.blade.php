@php $isEdit = isset($adjustment) && $adjustment->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Adjustment Type</label>
    <input type="text" name="type"
           value="{{ old('type', $adjustment->type ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder='e.g., "Ergonomic chair"' required>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">In Place From</label>
    <input type="date" name="in_place_from"
           value="{{ old('in_place_from', optional($adjustment->in_place_from ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('in_place_from') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $adjustment->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.adjustments.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
