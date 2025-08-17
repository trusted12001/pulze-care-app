@php
  // Helpers for old() + model defaults
  $val = fn($field, $default = '') => old($field, $serviceUser->{$field} ?? $default);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  {{-- Identity --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">First Name</label>
    <input name="first_name" value="{{ $val('first_name') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Last Name</label>
    <input name="last_name" value="{{ $val('last_name') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Preferred Name</label>
    <input name="preferred_name" value="{{ $val('preferred_name') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Date of Birth</label>
    <input type="date" name="date_of_birth" value="{{ $val('date_of_birth') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('date_of_birth') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Contact & Address --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Primary Phone</label>
    <input name="primary_phone" value="{{ $val('primary_phone') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Email</label>
    <input type="email" name="email" value="{{ $val('email') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Address Line 1</label>
    <input name="address_line1" value="{{ $val('address_line1') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('address_line1') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Address Line 2</label>
    <input name="address_line2" value="{{ $val('address_line2') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">City</label>
    <input name="city" value="{{ $val('city') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Postcode</label>
    <input name="postcode" value="{{ $val('postcode') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('postcode') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Placement --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Placement Type</label>
    @php
      $placeOpts = ['care_home' => 'Care Home', 'supported_living' => 'Supported Living', 'domiciliary' => 'Domiciliary', 'respite' => 'Respite'];
    @endphp
    <select name="placement_type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">-- Select --</option>
      @foreach($placeOpts as $valKey => $label)
        <option value="{{ $valKey }}" {{ $val('placement_type') === $valKey ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Room Number</label>
    <input name="room_number" value="{{ $val('room_number') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Admission Date</label>
    <input type="date" name="admission_date" value="{{ $val('admission_date') }}" required
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
    @error('admission_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Status</label>
    @php
      $statusOpts = ['active'=>'Active', 'on_leave'=>'On Leave', 'discharged'=>'Discharged', 'deceased'=>'Deceased'];
    @endphp
    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      @foreach($statusOpts as $k => $label)
        <option value="{{ $k }}" {{ $val('status','active') === $k ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
  </div>

  {{-- GP (MVP) --}}
  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">GP Practice Name</label>
    <input name="gp_practice_name" value="{{ $val('gp_practice_name') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">GP Phone</label>
    <input name="gp_phone" value="{{ $val('gp_phone') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Pharmacy Phone</label>
    <input name="pharmacy_phone" value="{{ $val('pharmacy_phone') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
  </div>

</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ isset($serviceUser) ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.service-users.index') }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
