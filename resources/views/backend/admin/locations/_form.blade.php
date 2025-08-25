@php
  $isEdit = isset($location) && $location->exists;
@endphp

@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

  <div class="md:col-span-2">
    <label for="name" class="block mb-1 font-medium text-gray-800">Location Name</label>
    <input id="name" type="text" name="name"
           value="{{ old('name', $location->name ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
           @if ($isEdit)
               {{ 'readonly' }}
           @endif
            required>
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="type" class="block mb-1 font-medium text-gray-800">Type</label>
    @php
      $types = ['care_home','supported_living','day_centre','domiciliary'];
      $typeValue = old('type', $location->type ?? 'domiciliary');
    @endphp
    <select id="type" name="type"
            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      @foreach($types as $t)
        <option value="{{ $t }}" @selected($typeValue===$t)>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
      @endforeach
    </select>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="status" class="block mb-1 font-medium text-gray-800">Status</label>
    @php
      $statuses = ['active','inactive'];
      $statusValue = old('status', $location->status ?? 'active');
    @endphp
    <select id="status" name="status"
            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected($statusValue===$st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="geofence_radius_m" class="block mb-1 font-medium text-gray-800">Geofence Radius (m)</label>
    <input id="geofence_radius_m" type="number" min="10" max="1000" step="1" name="geofence_radius_m"
           value="{{ old('geofence_radius_m', $location->geofence_radius_m ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2"
           placeholder="e.g., 100">
    @error('geofence_radius_m') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Address</label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <input type="text" name="address_line1"
             value="{{ old('address_line1', $location->address_line1 ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="Address line 1">
      <input type="text" name="address_line2"
             value="{{ old('address_line2', $location->address_line2 ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="Address line 2">
      <input type="text" name="city"
             value="{{ old('city', $location->city ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="City">
      <input type="text" name="postcode"
             value="{{ old('postcode', $location->postcode ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="Postcode">
      <input type="text" name="country"
             value="{{ old('country', $location->country ?? '') }}"
             class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" placeholder="Country">
    </div>
    @error('address_line1') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    @error('postcode') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="phone" class="block mb-1 font-medium text-gray-800">Phone</label>
    <input id="phone" type="text" name="phone"
           value="{{ old('phone', $location->phone ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="email" class="block mb-1 font-medium text-gray-800">Email</label>
    <input id="email" type="email" name="email"
           value="{{ old('email', $location->email ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="lat" class="block mb-1 font-medium text-gray-800">Latitude</label>
    <input id="lat" type="number" step="0.0000001" name="lat"
           value="{{ old('lat', $location->lat ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('lat') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="lng" class="block mb-1 font-medium text-gray-800">Longitude</label>
    <input id="lng" type="number" step="0.0000001" name="lng"
           value="{{ old('lng', $location->lng ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('lng') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ isset($location) ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.locations.index') }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
