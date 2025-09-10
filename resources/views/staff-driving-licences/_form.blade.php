@php $isEdit = isset($driving_licence) && $driving_licence->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Licence Number</label>
    <input type="text" name="licence_number"
           value="{{ old('licence_number', $driving_licence->licence_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="(optional)">
    @error('licence_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Categories</label>
    <input type="text" name="categories"
           value="{{ old('categories', $driving_licence->categories ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., B, C1">
    @error('categories') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Expiry Date</label>
    <input type="date" name="expires_at"
           value="{{ old('expires_at', optional($driving_licence->expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="flex items-center gap-2">
    <input id="biz_ins" type="checkbox" name="business_insurance_confirmed" value="1"
           class="rounded" {{ old('business_insurance_confirmed', $driving_licence->business_insurance_confirmed ?? false) ? 'checked' : '' }}>
    <label for="biz_ins" class="font-medium text-gray-800">Business Insurance Confirmed</label>
    @error('business_insurance_confirmed') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Document File ID (optional)</label>
    <input type="number" name="document_file_id"
           value="{{ old('document_file_id', $driving_licence->document_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('document_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.driving-licences.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
