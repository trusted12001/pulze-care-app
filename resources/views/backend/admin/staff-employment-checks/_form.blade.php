@php
  $isEdit = isset($check) && $check->exists;
  $types = [
    'dbs_basic' => 'DBS (Basic)',
    'dbs_enhanced' => 'DBS (Enhanced)',
    'dbs_adult_first' => 'DBS Adult First',
    'barred_list_adult' => 'Barred List (Adult)',
    'barred_list_child' => 'Barred List (Child)',
    'rtw_passport' => 'Right to Work - Passport',
    'rtw_share_code' => 'Right to Work - Share Code',
    'proof_of_address' => 'Proof of Address',
    'reference' => 'Reference',
    'oh_clearance' => 'Occupational Health Clearance',
  ];
  $results = ['pass' => 'Pass', 'fail' => 'Fail', 'pending' => 'Pending'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Check Type</label>
    @php $ct = old('check_type', $check->check_type ?? 'dbs_enhanced'); @endphp
    <select name="check_type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($types as $val => $label)
        <option value="{{ $val }}" @selected($ct===$val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('check_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Result</label>
    @php $res = old('result', $check->result ?? 'pending'); @endphp
    <select name="result" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($results as $val => $label)
        <option value="{{ $val }}" @selected($res===$val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('result') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Reference No</label>
    <input type="text" name="reference_no" value="{{ old('reference_no', $check->reference_no ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('reference_no') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Issued At</label>
    <input type="date" name="issued_at"
           value="{{ old('issued_at', optional($check->issued_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('issued_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Expires At</label>
    <input type="date" name="expires_at"
           value="{{ old('expires_at', optional($check->expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Verified By</label>
    <select name="checked_by_user_id" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">— Select —</option>
      @foreach(($verifiers ?? collect()) as $v)
        @php $name = $v->full_name ?? trim("{$v->first_name} {$v->other_names} {$v->last_name}"); @endphp
        <option value="{{ $v->id }}" @selected(old('checked_by_user_id', $check->checked_by_user_id ?? null)===$v->id)>{{ $name }} — {{ $v->email }}</option>
      @endforeach
    </select>
    @error('checked_by_user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Evidence File ID</label>
    <input type="number" name="evidence_file_id" value="{{ old('evidence_file_id', $check->evidence_file_id ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="(optional)">
    @error('evidence_file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $check->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.employment-checks.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
