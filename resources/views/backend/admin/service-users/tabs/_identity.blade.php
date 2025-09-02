<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'identity']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm mb-1">Gender Identity</label>
      <input type="text" name="gender_identity" class="w-full border rounded px-3 py-2"
             value="{{ old('gender_identity', $su->gender_identity) }}">
      @error('gender_identity')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">Pronouns</label>
      <input type="text" name="pronouns" class="w-full border rounded px-3 py-2"
             placeholder="e.g., she/her, he/him, they/them"
             value="{{ old('pronouns', $su->pronouns) }}">
      @error('pronouns')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">NHS Number</label>
      <input type="text" name="nhs_number" class="w-full border rounded px-3 py-2"
             placeholder="123 456 7890"
             value="{{ old('nhs_number', $su->nhs_number) }}">
      @error('nhs_number')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">National Insurance No</label>
      <input type="text" name="national_insurance_no" class="w-full border rounded px-3 py-2"
             placeholder="QQ123456C"
             value="{{ old('national_insurance_no', $su->national_insurance_no) }}">
      @error('national_insurance_no')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Photo Path / URL</label>
      <input type="text" name="photo_path" class="w-full border rounded px-3 py-2"
             placeholder="/storage/avatars/jane.jpg or https://…"
             value="{{ old('photo_path', $su->photo_path) }}">
      @error('photo_path')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Identity</button>
  </div>
</form>
