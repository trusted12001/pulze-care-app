@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

  {{-- Identity --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">First Name</label>
    <input name="first_name" value="{{ old('first_name', $su->first_name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Middle Name</label>
    <input name="middle_name" value="{{ old('middle_name', $su->middle_name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Last Name</label>
    <input name="last_name" value="{{ old('last_name', $su->last_name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Preferred Name</label>
    <input name="preferred_name" value="{{ old('preferred_name', $su->preferred_name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Date of Birth</label>
    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($su->date_of_birth ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('date_of_birth') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Sex at Birth</label>
    <input name="sex_at_birth" value="{{ old('sex_at_birth', $su->sex_at_birth ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>

  {{-- Contact --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Primary Phone</label>
    <input name="primary_phone" value="{{ old('primary_phone', $su->primary_phone ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Secondary Phone</label>
    <input name="secondary_phone" value="{{ old('secondary_phone', $su->secondary_phone ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Email</label>
    <input type="email" name="email" value="{{ old('email', $su->email ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>

  {{-- Address --}}
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Address</label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <input name="address_line1" placeholder="Address line 1"
             value="{{ old('address_line1', $su->address_line1 ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      <input name="address_line2" placeholder="Address line 2"
             value="{{ old('address_line2', $su->address_line2 ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <input name="city" placeholder="City"
             value="{{ old('city', $su->city ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      <input name="county" placeholder="County"
             value="{{ old('county', $su->county ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <input name="postcode" placeholder="Postcode"
             value="{{ old('postcode', $su->postcode ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      <input name="country" placeholder="Country"
             value="{{ old('country', $su->country ?? 'UK') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    </div>
  </div>


  {{-- Placement --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Placement Type</label>
    @php
      $placeOpts = ['care_home' => 'Care Home', 'supported_living' => 'Supported Living', 'day_center' => 'Day Center', 'domiciliary' => 'Domiciliary'];
    @endphp
    <select name="placement_type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">-- Select --</option>
      @foreach($placeOpts as $valKey => $label)
        <option value="{{ $valKey }}" @selected(old('placement_type', $su->placement_type ?? '') == $valKey)>{{ $label }}</option>

      @endforeach
    </select>
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Location</label>
    <select name="location_id" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">— Select —</option>
      @foreach($locations as $loc)
        @php $label = $loc->name . ($loc->city ? " ({$loc->city})" : '') . ($loc->postcode ? " - {$loc->postcode}" : '') @endphp
        <option value="{{ $loc->id }}" @selected(old('location_id', $su->location_id ?? '') == $loc->id)>{{ $label }}</option>
      @endforeach
    </select>
    @error('location_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Room Number</label>
    <input name="room_number" value="{{ old('room_number', $su->room_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>

  {{-- Admission / Status --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Admission Date</label>
    <input type="date" name="admission_date" value="{{ old('admission_date', optional($su->admission_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('admission_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Expected Discharge</label>
    <input type="date" name="expected_discharge_date" value="{{ old('expected_discharge_date', optional($su->expected_discharge_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Discharge Date</label>
    <input type="date" name="discharge_date" value="{{ old('discharge_date', optional($su->discharge_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Status</label>
    @php $statuses = ['active','inactive','discharged']; $val = old('status', $su->status ?? 'active'); @endphp
    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected($val===$st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Diet Type</label>
    <input name="diet_type" value="{{ old('diet_type', $su->diet_type ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
  </div>

  {{-- Optional clinical flags (example subset to keep the form readable) --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Allergies (summary)</label>
    <textarea name="allergies_summary" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('allergies_summary', $su->allergies_summary ?? '') }}</textarea>
  </div>

</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ isset($su) ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.service-users.index') }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
