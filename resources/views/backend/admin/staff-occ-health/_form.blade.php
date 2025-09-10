@php
  $isEdit = isset($occ_health) && $occ_health->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="flex items-center gap-2">
    <input id="cleared_for_role" type="checkbox" name="cleared_for_role" value="1"
           class="rounded"
           {{ old('cleared_for_role', $occ_health->cleared_for_role ?? false) ? 'checked' : '' }}>
    <label for="cleared_for_role" class="font-medium text-gray-800">Cleared for role</label>
    @error('cleared_for_role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Assessed At</label>
    <input type="date" name="assessed_at"
           value="{{ old('assessed_at', optional($occ_health->assessed_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('assessed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Restrictions</label>
    <textarea name="restrictions" rows="4"
              class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
    >{{ old('restrictions', $occ_health->restrictions ?? '') }}</textarea>
    @error('restrictions') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Review Due</label>
    <input type="date" name="review_due_at"
           value="{{ old('review_due_at', optional($occ_health->review_due_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('review_due_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.occ-health.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
@php
  $isEdit = isset($occ_health) && $occ_health->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="flex items-center gap-2">
    <input id="cleared_for_role" type="checkbox" name="cleared_for_role" value="1"
           class="rounded"
           {{ old('cleared_for_role', $occ_health->cleared_for_role ?? false) ? 'checked' : '' }}>
    <label for="cleared_for_role" class="font-medium text-gray-800">Cleared for role</label>
    @error('cleared_for_role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Assessed At</label>
    <input type="date" name="assessed_at"
           value="{{ old('assessed_at', optional($occ_health->assessed_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('assessed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Restrictions</label>
    <textarea name="restrictions" rows="4"
              class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
    >{{ old('restrictions', $occ_health->restrictions ?? '') }}</textarea>
    @error('restrictions') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Review Due</label>
    <input type="date" name="review_due_at"
           value="{{ old('review_due_at', optional($occ_health->review_due_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('review_due_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.occ-health.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
