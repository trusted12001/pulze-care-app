<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'preferences']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- Ethnicity --}}
    <div>
      <label class="block text-sm mb-1">Ethnicity</label>
      <input type="text"
             name="ethnicity"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., White British, Black Caribbean, Asian Indian…"
             value="{{ old('ethnicity', $su->ethnicity) }}">
      @error('ethnicity')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Religion --}}
    <div>
      <label class="block text-sm mb-1">Religion / Belief</label>
      <input type="text"
             name="religion"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Christian, Muslim, Hindu, Jewish, None…"
             value="{{ old('religion', $su->religion) }}">
      @error('religion')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Primary Language --}}
    <div>
      <label class="block text-sm mb-1">Primary Language</label>
      <input type="text"
             name="primary_language"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., English, Urdu, Polish, BSL…"
             value="{{ old('primary_language', $su->primary_language) }}">
      @error('primary_language')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Interpreter Required --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="interpreter_required" value="0">
      <input id="interpreter_required" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="interpreter_required" value="1"
             @checked(old('interpreter_required', (int)$su->interpreter_required) == 1)
             class="mt-1">
      <label for="interpreter_required" class="select-none">
        <span class="font-medium">Interpreter Required</span>
        <p class="text-sm text-gray-500">Tick if a language or BSL interpreter is usually required.</p>
      </label>
    </div>
    @error('interpreter_required')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Personal Preferences --}}
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Personal Preferences</label>
      <textarea name="personal_preferences" rows="5"
                class="w-full border rounded px-3 py-2"
                placeholder="Daily routines, cultural needs, activities enjoyed, sleeping patterns, privacy, preferred staff attributes…">{{ old('personal_preferences', $su->personal_preferences) }}</textarea>
      @error('personal_preferences')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Food Preferences --}}
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Food Preferences</label>
      <textarea name="food_preferences" rows="5"
                class="w-full border rounded px-3 py-2"
                placeholder="Dietary style (e.g., vegetarian/halal), likes/dislikes, textures, mealtime routines…">{{ old('food_preferences', $su->food_preferences) }}</textarea>
      @error('food_preferences')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Preferences
    </button>
  </div>
</form>
