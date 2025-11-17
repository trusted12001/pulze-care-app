@csrf
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">

  {{-- Identity --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      First Name <span class="text-red-500">*</span>
    </label>
    <input name="first_name" value="{{ old('first_name', $su->first_name ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
    @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Middle Name</label>
    <input name="middle_name" value="{{ old('middle_name', $su->middle_name ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Last Name <span class="text-red-500">*</span>
    </label>
    <input name="last_name" value="{{ old('last_name', $su->last_name ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
    @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Preferred Name</label>
    <input name="preferred_name" value="{{ old('preferred_name', $su->preferred_name ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>

  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Date of Birth <span class="text-red-500">*</span>
    </label>
    <input type="date" name="date_of_birth"
      value="{{ old('date_of_birth', optional($su->date_of_birth ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
    @error('date_of_birth') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Sex at Birth</label>
    <input name="sex_at_birth" value="{{ old('sex_at_birth', $su->sex_at_birth ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>

  {{-- Contact --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Primary Phone</label>
    <input name="primary_phone" value="{{ old('primary_phone', $su->primary_phone ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Secondary Phone</label>
    <input name="secondary_phone" value="{{ old('secondary_phone', $su->secondary_phone ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  <div class="sm:col-span-2">
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', $su->email ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>

  {{-- Address --}}
  <div class="sm:col-span-2">
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Address</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
      <input name="address_line1" placeholder="Address line 1"
        value="{{ old('address_line1', $su->address_line1 ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        required>
      <input name="address_line2" placeholder="Address line 2"
        value="{{ old('address_line2', $su->address_line2 ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <input name="city" placeholder="City" value="{{ old('city', $su->city ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        required>
      <input name="county" placeholder="County" value="{{ old('county', $su->county ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <input name="postcode" placeholder="Postcode" value="{{ old('postcode', $su->postcode ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        required>
      <input name="country" placeholder="Country" value="{{ old('country', $su->country ?? 'UK') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        required>
    </div>
  </div>

  {{-- Placement --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Placement Type</label>
    @php
      $placeOpts = ['care_home' => 'Care Home', 'supported_living' => 'Supported Living', 'day_center' => 'Day Center', 'domiciliary' => 'Domiciliary'];
    @endphp
    <select name="placement_type"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <option value="">-- Select --</option>
      @foreach($placeOpts as $valKey => $label)
        <option value="{{ $valKey }}" @selected(old('placement_type', $su->placement_type ?? '') == $valKey)>{{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Location</label>
    <select name="location_id"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      <option value="">— Select —</option>
      @foreach($locations as $loc)
        @php $label = $loc->name . ($loc->city ? " ({$loc->city})" : '') . ($loc->postcode ? " - {$loc->postcode}" : '') @endphp
        <option value="{{ $loc->id }}" @selected(old('location_id', $su->location_id ?? '') == $loc->id)>{{ $label }}
        </option>
      @endforeach
    </select>
    @error('location_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Room Number</label>
    <input name="room_number" value="{{ old('room_number', $su->room_number ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>

  {{-- Admission / Status --}}
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Admission Date <span class="text-red-500">*</span>
    </label>
    <input type="date" name="admission_date"
      value="{{ old('admission_date', optional($su->admission_date ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      required>
    @error('admission_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Expected Discharge</label>
    <input type="date" name="expected_discharge_date"
      value="{{ old('expected_discharge_date', optional($su->expected_discharge_date ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Discharge Date</label>
    <input type="date" name="discharge_date"
      value="{{ old('discharge_date', optional($su->discharge_date ?? null)->format('Y-m-d')) }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Status</label>
    @php $statuses = ['active', 'inactive', 'discharged'];
    $val = old('status', $su->status ?? 'active'); @endphp
    <select name="status"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected($val === $st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Diet Type</label>
    <input name="diet_type" value="{{ old('diet_type', $su->diet_type ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>

  {{-- Optional clinical flags (example subset to keep the form readable) --}}
  <div class="sm:col-span-2">
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Allergies (summary)</label>
    <textarea name="allergies_summary" rows="3"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('allergies_summary', $su->allergies_summary ?? '') }}</textarea>
  </div>

</div>

{{-- Form Actions --}}
<div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
  <a href="{{ route('backend.admin.service-users.index') }}"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
    <i class="ph ph-x"></i>
    <span>Cancel</span>
  </a>
  <button type="submit"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
    <i class="ph ph-check"></i>
    <span>{{ isset($su) ? 'Update Service User' : 'Create Service User' }}</span>
  </button>
</div>