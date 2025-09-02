<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'gp_pharmacy']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- GP Practice --}}
    <div class="md:col-span-2">
      <h4 class="font-medium mb-2">GP Practice</h4>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Practice Name</label>
          <input type="text" name="gp_practice_name"
                 class="w-full border rounded px-3 py-2"
                 value="{{ old('gp_practice_name', $su->gp_practice_name) }}">
          @error('gp_practice_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm mb-1">Contact Name</label>
          <input type="text" name="gp_contact_name"
                 class="w-full border rounded px-3 py-2"
                 value="{{ old('gp_contact_name', $su->gp_contact_name) }}">
          @error('gp_contact_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm mb-1">Phone</label>
          <input type="text" name="gp_phone"
                 class="w-full border rounded px-3 py-2"
                 placeholder="+44 20 7123 4567"
                 value="{{ old('gp_phone', $su->gp_phone) }}">
          @error('gp_phone')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm mb-1">Email</label>
          <input type="email" name="gp_email"
                 class="w-full border rounded px-3 py-2"
                 placeholder="practice@example.nhs.uk"
                 value="{{ old('gp_email', $su->gp_email) }}">
          @error('gp_email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm mb-1">Address</label>
          <textarea name="gp_address" rows="3"
                    class="w-full border rounded px-3 py-2"
                    placeholder="Street, Town/City, County, Postcode">{{ old('gp_address', $su->gp_address) }}</textarea>
          @error('gp_address')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- Pharmacy --}}
    <div class="md:col-span-2 mt-2">
      <h4 class="font-medium mb-2">Pharmacy</h4>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Pharmacy Name</label>
          <input type="text" name="pharmacy_name"
                 class="w-full border rounded px-3 py-2"
                 value="{{ old('pharmacy_name', $su->pharmacy_name) }}">
          @error('pharmacy_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm mb-1">Phone</label>
          <input type="text" name="pharmacy_phone"
                 class="w-full border rounded px-3 py-2"
                 placeholder="+44 20 7555 0000"
                 value="{{ old('pharmacy_phone', $su->pharmacy_phone) }}">
          @error('pharmacy_phone')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save GP & Pharmacy
    </button>
  </div>
</form>
