@php
  $isEdit = isset($qualification) && $qualification->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Level</label>
    <input type="text" name="level" value="{{ old('level', $qualification->level ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., NVQ3, BSc" required>
    @error('level') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Title</label>
    <input type="text" name="title" value="{{ old('title', $qualification->title ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., Health & Social Care" required>
    @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Institution</label>
    <input type="text" name="institution" value="{{ old('institution', $qualification->institution ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., Open University">
    @error('institution') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Awarded At</label>
    <input type="date" name="awarded_at"
           value="{{ old('awarded_at', optional($qualification->awarded_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('awarded_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Certificate File ID</label>
    <input type="number" name="certificate_file_id"
           value="{{ old('certificate_file_id', $qualification->certificate_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="(optional)">
    @error('certificate_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.qualifications.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
