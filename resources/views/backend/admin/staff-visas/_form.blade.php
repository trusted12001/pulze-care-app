@php
  $isEdit = isset($visa) && $visa->exists;

  $cats = [
    'settled' => 'Settled',
    'pre_settled' => 'Pre-settled',
    'skilled_worker' => 'Skilled Worker',
    'student' => 'Student',
    'family' => 'Family',
    'british' => 'British (no visa)',
    'irish' => 'Irish (no visa)',
    'other' => 'Other',
  ];
  $statuses = ['active' => 'Active', 'expired' => 'Expired', 'pending' => 'Pending', 'revoked' => 'Revoked'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  {{-- Status & Category --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Status</label>
    @php $st = old('status', $visa->status ?? 'active'); @endphp
    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($statuses as $val => $label)
        <option value="{{ $val }}" @selected($st===$val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Immigration Category</label>
    @php $cat = old('immigration_category', $visa->immigration_category ?? 'other'); @endphp
    <select name="immigration_category" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($cats as $val => $label)
        <option value="{{ $val }}" @selected($cat===$val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('immigration_category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Nationality / Passport --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Nationality</label>
    <input type="text" name="nationality" value="{{ old('nationality', $visa->nationality ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('nationality') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Passport Number</label>
    <input type="text" name="passport_number" value="{{ old('passport_number', $visa->passport_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('passport_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Passport Expires</label>
    <input type="date" name="passport_expires_at"
           value="{{ old('passport_expires_at', optional($visa->passport_expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('passport_expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Visa/Permit Details --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Visa Number</label>
    <input type="text" name="visa_number" value="{{ old('visa_number', $visa->visa_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('visa_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">BRP Number</label>
    <input type="text" name="brp_number" value="{{ old('brp_number', $visa->brp_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('brp_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">BRP Expires</label>
    <input type="date" name="brp_expires_at"
           value="{{ old('brp_expires_at', optional($visa->brp_expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('brp_expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">RTW Share Code</label>
    <input type="text" name="share_code" value="{{ old('share_code', $visa->share_code ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., ABC-123-XYZ">
    @error('share_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Sponsor Licence #</label>
    <input type="text" name="sponsor_licence_number"
           value="{{ old('sponsor_licence_number', $visa->sponsor_licence_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('sponsor_licence_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">CoS Number</label>
    <input type="text" name="cos_number" value="{{ old('cos_number', $visa->cos_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('cos_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Issue/Expiry --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Issued Country</label>
    <input type="text" name="issued_country" value="{{ old('issued_country', $visa->issued_country ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('issued_country') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Issued At</label>
    <input type="date" name="issued_at"
           value="{{ old('issued_at', optional($visa->issued_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('issued_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Expires At</label>
    <input type="date" name="expires_at"
           value="{{ old('expires_at', optional($visa->expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Restrictions --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Weekly Hours Cap</label>
    <input type="number" step="0.5" min="0" max="168" name="weekly_hours_cap"
           value="{{ old('weekly_hours_cap', $visa->weekly_hours_cap ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="e.g., 20.0">
    @error('weekly_hours_cap') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="flex items-center gap-2">
    <input type="checkbox" name="term_time_only" value="1" id="term_time_only"
           class="rounded"
           {{ old('term_time_only', $visa->term_time_only ?? false) ? 'checked' : '' }}>
    <label for="term_time_only" class="font-medium text-gray-800">Term-time only (Student)</label>
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Restrictions / Notes</label>
    <textarea name="restrictions" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('restrictions', $visa->restrictions ?? '') }}</textarea>
    @error('restrictions') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Evidence / Verified by --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Evidence File ID</label>
    <input type="number" name="evidence_file_id" value="{{ old('evidence_file_id', $visa->evidence_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="(optional)">
    @error('evidence_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Verified By</label>
    <select name="checked_by_user_id" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">— Select —</option>
      @foreach(($verifiers ?? collect()) as $v)
        @php $vname = $v->full_name ?? trim("{$v->first_name} {$v->other_names} {$v->last_name}"); @endphp
        <option value="{{ $v->id }}" @selected(old('checked_by_user_id', $visa->checked_by_user_id ?? null)===$v->id)>{{ $vname }} — {{ $v->email }}</option>
      @endforeach
    </select>
    @error('checked_by_user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Internal Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $visa->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.visas.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
