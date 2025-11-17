@php
  $isEdit = isset($location) && $location->exists;
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">

  <div class="sm:col-span-2">
    <label for="name" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Location Name <span class="text-red-500">*</span>
    </label>
    <input id="name" type="text" name="name" value="{{ old('name', $location->name ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $isEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
      @if ($isEdit) readonly @endif required>
    @if($isEdit)
      <p class="text-xs text-gray-500 mt-1">Location name cannot be changed after creation.</p>
    @endif
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="type" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Type <span class="text-red-500">*</span>
    </label>
    @php
      $types = ['care_home', 'supported_living', 'day_centre', 'domiciliary'];
      $typeValue = old('type', $location->type ?? 'domiciliary');
    @endphp
    <select id="type" name="type"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      @foreach($types as $t)
        <option value="{{ $t }}" @selected($typeValue === $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
      @endforeach
    </select>
    @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="status" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
      Status <span class="text-red-500">*</span>
    </label>
    @php
      $statuses = ['active', 'inactive'];
      $statusValue = old('status', $location->status ?? 'active');
    @endphp
    <select id="status" name="status"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected($statusValue === $st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="geofence_radius_m" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Geofence Radius
      (m)</label>
    <input id="geofence_radius_m" type="number" min="10" max="1000" step="1" name="geofence_radius_m"
      value="{{ old('geofence_radius_m', $location->geofence_radius_m ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      placeholder="e.g., 100">
    @error('geofence_radius_m') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="sm:col-span-2">
    <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Address</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
      <input type="text" name="address_line1" value="{{ old('address_line1', $location->address_line1 ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        placeholder="Address line 1">
      <input type="text" name="address_line2" value="{{ old('address_line2', $location->address_line2 ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        placeholder="Address line 2">
      <input type="text" name="city" value="{{ old('city', $location->city ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        placeholder="City">
      <input type="text" name="postcode" value="{{ old('postcode', $location->postcode ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        placeholder="Postcode">
      <input type="text" name="country" value="{{ old('country', $location->country ?? '') }}"
        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
        placeholder="Country">
    </div>
    @error('address_line1') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    @error('postcode') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="phone" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Phone</label>
    <input id="phone" type="text" name="phone" value="{{ old('phone', $location->phone ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="email" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $location->email ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="lat" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Latitude</label>
    <input id="lat" type="number" step="0.0000001" name="lat" value="{{ old('lat', $location->lat ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('lat') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="lng" class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Longitude</label>
    <input id="lng" type="number" step="0.0000001" name="lng" value="{{ old('lng', $location->lng ?? '') }}"
      class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    @error('lng') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

</div>

{{-- Form Actions --}}
<div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
  <a href="{{ route('backend.admin.locations.index') }}"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
    <i class="ph ph-x"></i>
    <span>Cancel</span>
  </a>
  <button type="submit"
    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
    <i class="ph ph-check"></i>
    <span>{{ $isEdit ? 'Update Location' : 'Create Location' }}</span>
  </button>
</div>