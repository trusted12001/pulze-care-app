@php
  $isEdit = isset($staffProfile) && $staffProfile->exists;
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
  {{-- User --}}
  <div class="{{ $isEdit ? 'sm:col-span-1' : 'sm:col-span-2' }}">
    <label for="user_id" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      User Account @if(!$isEdit) <span class="text-red-500">*</span> @endif
    </label>

    @if(!$isEdit)
      <select id="user_id" name="user_id"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        required>
        <option value="">-- Select user --</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}" {{ (string) old('user_id', $staffProfile->user_id ?? '') === (string) $u->id ? 'selected' : '' }}>
            {{ $u->full_name ?? trim("{$u->first_name} {$u->other_names} {$u->last_name}") }} — {{ $u->email }}
          </option>
        @endforeach
      </select>
      @error('user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    @else
      <input type="text"
        value="{{ ($staffProfile->user->full_name ?? '—') . ' — ' . ($staffProfile->user->email ?? '—') }}"
        class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base cursor-not-allowed"
        disabled>
      <p class="text-xs text-gray-500 mt-1">Linked user cannot be changed.</p>
    @endif
  </div>

  {{-- Employment Status --}}
  <div>
    <label for="employment_status" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Employment Status <span class="text-red-500">*</span>
    </label>
    @php
      $statusOptions = ['active', 'on_leave', 'inactive'];
      $statusValue = old('employment_status', $staffProfile->employment_status ?? 'active');
    @endphp
    <select id="employment_status" name="employment_status"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      @foreach($statusOptions as $st)
        <option value="{{ $st }}" @selected($statusValue === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
      @endforeach
    </select>
    @error('employment_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Job Title --}}
  <div>
    <label for="job_title" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Job Title</label>
    <input id="job_title" type="text" name="job_title" value="{{ old('job_title', $staffProfile->job_title ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      placeholder="e.g., Support Worker">
    @error('job_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Date of Birth --}}
  <div>
    <label for="date_of_birth" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Date of Birth <span class="text-red-500">*</span>
    </label>
    <input id="date_of_birth" type="date" name="date_of_birth"
      value="{{ old('date_of_birth', optional($staffProfile->date_of_birth ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
    @error('date_of_birth') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Work Email --}}
  <div>
    <label for="work_email" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Work Email</label>
    <input id="work_email" type="email" name="work_email"
      value="{{ old('work_email', $staffProfile->work_email ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('work_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Phone --}}
  <div>
    <label for="phone" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Phone</label>
    <input id="phone" type="text" name="phone" value="{{ old('phone', $staffProfile->phone ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Employment Type --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Employment Type <span class="text-red-500">*</span>
    </label>
    @php $type = old('employment_type', $staffProfile->employment_type ?? 'employee'); @endphp
    <select name="employment_type"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
      @foreach(['employee', 'worker', 'contractor', 'bank', 'agency'] as $opt)
        <option value="{{ $opt }}" @selected($type === $opt)>{{ ucfirst($opt) }}</option>
      @endforeach
    </select>
    @error('employment_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Engagement Basis --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Engagement Basis <span class="text-red-500">*</span>
    </label>
    @php $basis = old('engagement_basis', $staffProfile->engagement_basis ?? 'full_time'); @endphp
    <select name="engagement_basis"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
      @foreach(['full_time', 'part_time', 'casual', 'zero_hours'] as $opt)
        <option value="{{ $opt }}" @selected($basis === $opt)>{{ ucwords(str_replace('_', ' ', $opt)) }}</option>
      @endforeach
    </select>
    @error('engagement_basis') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Dates --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Hire Date</label>
    <input type="date" name="hire_date"
      value="{{ old('hire_date', optional($staffProfile->hire_date ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('hire_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Start in Post</label>
    <input type="date" name="start_in_post"
      value="{{ old('start_in_post', optional($staffProfile->start_in_post ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('start_in_post') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">End in Post</label>
    <input type="date" name="end_in_post"
      value="{{ old('end_in_post', optional($staffProfile->end_in_post ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('end_in_post') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Organisation --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Work Location</label>
    <select name="work_location_id"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <option value="">— Select —</option>
      @foreach(($locations ?? collect()) as $loc)
        <option value="{{ $loc->id }}" @selected(old('work_location_id', $staffProfile->work_location_id ?? null) === $loc->id)>{{ $loc->name }}</option>
      @endforeach
    </select>
    @error('work_location_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Line Manager</label>
    <select name="line_manager_user_id"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <option value="">— Select —</option>
      @foreach(($managers ?? collect()) as $mgr)
        @php $mgrName = $mgr->full_name ?? trim("{$mgr->first_name} {$mgr->other_names} {$mgr->last_name}"); @endphp
        <option value="{{ $mgr->id }}" @selected(old('line_manager_user_id', $staffProfile->line_manager_user_id ?? null) === $mgr->id)>{{ $mgrName }}</option>
      @endforeach
    </select>
    @error('line_manager_user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- DBS Number --}}
  <div>
    <label for="dbs_number" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">DBS Number</label>
    <input id="dbs_number" type="text" name="dbs_number"
      value="{{ old('dbs_number', $staffProfile->dbs_number ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('dbs_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- DBS Issued At --}}
  <div>
    <label for="dbs_issued_at" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">DBS Issued At</label>
    <input id="dbs_issued_at" type="date" name="dbs_issued_at"
      value="{{ old('dbs_issued_at', optional($staffProfile->dbs_issued_at ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('dbs_issued_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- DBS Update Service --}}
  <div class="sm:col-span-2">
    <label class="inline-flex items-center space-x-2">
      <input type="checkbox" name="dbs_update_service" value="1"
        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('dbs_update_service', $staffProfile->dbs_update_service ?? false) ? 'checked' : '' }}>
      <span class="text-sm text-gray-700">Enrolled in DBS Update Service</span>
    </label>
    @error('dbs_update_service') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Mandatory Training Completed At --}}
  <div>
    <label for="mandatory_training_completed_at" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Mandatory Training Completed At
    </label>
    <input id="mandatory_training_completed_at" type="datetime-local" name="mandatory_training_completed_at"
      value="{{ old('mandatory_training_completed_at', optional($staffProfile->mandatory_training_completed_at ?? null)->format('Y-m-d\TH:i')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('mandatory_training_completed_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Right to Work Verified At --}}
  <div>
    <label for="rtw_verified" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Right to Work Verified
      At</label>
    <input id="rtw_verified" type="datetime-local" name="right_to_work_verified_at"
      value="{{ old('right_to_work_verified_at', optional($staffProfile->right_to_work_verified_at ?? null)->format('Y-m-d\TH:i')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('right_to_work_verified_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- NMC PIN --}}
  <div>
    <label for="nmc_pin" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">NMC PIN</label>
    <input id="nmc_pin" type="text" name="nmc_pin" value="{{ old('nmc_pin', $staffProfile->nmc_pin ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('nmc_pin') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- GPhC PIN --}}
  <div>
    <label for="gphc_pin" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">GPhC PIN</label>
    <input id="gphc_pin" type="text" name="gphc_pin" value="{{ old('gphc_pin', $staffProfile->gphc_pin ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('gphc_pin') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Notes --}}
  <div class="sm:col-span-2">
    <label for="notes" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Notes</label>
    <textarea id="notes" name="notes" rows="4"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('notes', $staffProfile->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

{{-- Form Actions --}}
<div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
  <a href="{{ route('backend.admin.staff-profiles.index') }}"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
    <i class="ph ph-x"></i>
    <span>Cancel</span>
  </a>
  <button type="submit"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
    <i class="ph ph-check"></i>
    <span>{{ $isEdit ? 'Update Profile' : 'Create Profile' }}</span>
  </button>
</div>