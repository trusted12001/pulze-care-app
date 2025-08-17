<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  {{-- User --}}
  <div>
    <label for="user_id" class="block mb-1 font-medium text-gray-800">User</label>
    <select id="user_id" name="user_id"
            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
            required>
      @foreach($users as $u)
        <option value="{{ $u->id }}" @selected(old('user_id', $staffProfile->user_id ?? '') == $u->id)>
          {{ $u->name }} — {{ $u->email }}
        </option>
      @endforeach
    </select>
    @error('user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Employment Status --}}
  <div>
    <label for="employment_status" class="block mb-1 font-medium text-gray-800">Employment Status</label>
    @php
      $statusOptions = ['active','on_leave','inactive'];
      $statusValue   = old('employment_status', $staffProfile->employment_status ?? 'active');
    @endphp
    <select id="employment_status" name="employment_status"
            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      @foreach($statusOptions as $st)
        <option value="{{ $st }}" @selected($statusValue === $st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
      @endforeach
    </select>
    @error('employment_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Job Title --}}
  <div>
    <label for="job_title" class="block mb-1 font-medium text-gray-800">Job Title</label>
    <input id="job_title" type="text" name="job_title"
           value="{{ old('job_title', $staffProfile->job_title ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
           placeholder="e.g., Support Worker">
    @error('job_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Work Email --}}
  <div>
    <label for="work_email" class="block mb-1 font-medium text-gray-800">Work Email</label>
    <input id="work_email" type="email" name="work_email"
           value="{{ old('work_email', $staffProfile->work_email ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('work_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Phone --}}
  <div>
    <label for="phone" class="block mb-1 font-medium text-gray-800">Phone</label>
    <input id="phone" type="text" name="phone"
           value="{{ old('phone', $staffProfile->phone ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- DBS Number --}}
  <div>
    <label for="dbs_number" class="block mb-1 font-medium text-gray-800">DBS Number</label>
    <input id="dbs_number" type="text" name="dbs_number"
           value="{{ old('dbs_number', $staffProfile->dbs_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('dbs_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- DBS Issued At (date) --}}
  <div>
    <label for="dbs_issued_at" class="block mb-1 font-medium text-gray-800">DBS Issued At</label>
    <input id="dbs_issued_at" type="date" name="dbs_issued_at"
           value="{{ old('dbs_issued_at', optional($staffProfile->dbs_issued_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('dbs_issued_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Mandatory Training Completed At (datetime-local) --}}
  <div>
    <label for="mandatory_training_completed_at" class="block mb-1 font-medium text-gray-800">
      Mandatory Training Completed At
    </label>
    <input id="mandatory_training_completed_at" type="datetime-local" name="mandatory_training_completed_at"
           value="{{ old('mandatory_training_completed_at', optional($staffProfile->mandatory_training_completed_at ?? null)->format('Y-m-d\TH:i')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('mandatory_training_completed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- NMC PIN --}}
  <div>
    <label for="nmc_pin" class="block mb-1 font-medium text-gray-800">NMC PIN</label>
    <input id="nmc_pin" type="text" name="nmc_pin"
           value="{{ old('nmc_pin', $staffProfile->nmc_pin ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('nmc_pin') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- GPhC PIN --}}
  <div>
    <label for="gphc_pin" class="block mb-1 font-medium text-gray-800">GPhC PIN</label>
    <input id="gphc_pin" type="text" name="gphc_pin"
           value="{{ old('gphc_pin', $staffProfile->gphc_pin ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('gphc_pin') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Right to Work Verified At (datetime-local) --}}
  <div class="md:col-span-2">
    <label for="rtw_verified" class="block mb-1 font-medium text-gray-800">Right to Work Verified At</label>
    <input id="rtw_verified" type="datetime-local" name="right_to_work_verified_at"
           value="{{ old('right_to_work_verified_at', optional($staffProfile->right_to_work_verified_at ?? null)->format('Y-m-d\TH:i')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('right_to_work_verified_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Notes --}}
  <div class="md:col-span-2">
    <label for="notes" class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea id="notes" name="notes" rows="4"
              class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $staffProfile->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ isset($staffProfile) ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.index') }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">
    Cancel
  </a>
</div>
