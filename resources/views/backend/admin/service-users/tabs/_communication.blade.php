<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'communication']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid gap-4">
    <div>
      <label class="block text-sm mb-1">Communication Needs</label>
      <textarea name="communication_needs" rows="6"
                class="w-full border rounded px-3 py-2"
                placeholder="E.g., BSL interpreter, picture exchange, simplified language, hearing aid, glasses, AAC device, best times to communicate…">{{ old('communication_needs', $su->communication_needs) }}</textarea>
      @error('communication_needs')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      <p class="text-xs text-gray-500 mt-1">Tip: include preferred style, supports, triggers to avoid, and any devices.</p>
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Communication
    </button>
  </div>
</form>
