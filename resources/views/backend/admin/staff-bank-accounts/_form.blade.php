@php
  $isEdit = isset($bank_account) && $bank_account->exists;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Account Holder</label>
    <input type="text" name="account_holder" value="{{ old('account_holder', $bank_account->account_holder ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('account_holder') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Sort Code</label>
    <input type="text" name="sort_code" value="{{ old('sort_code', $bank_account->sort_code ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('sort_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Account Number</label>
    <input type="text" name="account_number" value="{{ old('account_number', $bank_account->account_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('account_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Building Society Roll (optional)</label>
    <input type="text" name="building_society_roll" value="{{ old('building_society_roll', $bank_account->building_society_roll ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('building_society_roll') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.bank-accounts.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
