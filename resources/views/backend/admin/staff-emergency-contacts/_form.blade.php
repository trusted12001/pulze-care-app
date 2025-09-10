@php
  $isEdit = isset($contact) && $contact->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Name</label>
    <input type="text" name="name" value="{{ old('name', $contact->name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Relationship</label>
    <input type="text" name="relationship" value="{{ old('relationship', $contact->relationship ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('relationship') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $contact->phone ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Email (optional)</label>
    <input type="email" name="email" value="{{ old('email', $contact->email ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Address (optional)</label>
    <textarea name="address" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('address', $contact->address ?? '') }}</textarea>
    @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.emergency-contacts.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
