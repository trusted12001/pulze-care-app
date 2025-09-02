<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'risks']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- Fall Risk --}}
    <div>
      <label class="block text-sm mb-1">Fall Risk</label>
      <input type="text" name="fall_risk"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Low / Medium / High"
             value="{{ old('fall_risk', $su->fall_risk) }}">
      @error('fall_risk')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Choking Risk --}}
    <div>
      <label class="block text-sm mb-1">Choking Risk</label>
      <input type="text" name="choking_risk"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Low / Medium / High"
             value="{{ old('choking_risk', $su->choking_risk) }}">
      @error('choking_risk')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Pressure Ulcer Risk --}}
    <div>
      <label class="block text-sm mb-1">Pressure Ulcer Risk</label>
      <input type="text" name="pressure_ulcer_risk"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Braden scale result"
             value="{{ old('pressure_ulcer_risk', $su->pressure_ulcer_risk) }}">
      @error('pressure_ulcer_risk')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Wander / Elopement Risk --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="wander_elopement_risk" value="0">
      <input id="wander_elopement_risk" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="wander_elopement_risk" value="1"
             @checked(old('wander_elopement_risk', (int)$su->wander_elopement_risk) == 1)
             class="mt-1">
      <label for="wander_elopement_risk" class="select-none">
        <span class="font-medium">Wander / Elopement Risk</span>
        <p class="text-sm text-gray-500">Tick if at risk of wandering or leaving premises unsafely.</p>
      </label>
    </div>
    @error('wander_elopement_risk')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Safeguarding Flag --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="safeguarding_flag" value="0">
      <input id="safeguarding_flag" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="safeguarding_flag" value="1"
             @checked(old('safeguarding_flag', (int)$su->safeguarding_flag) == 1)
             class="mt-1">
      <label for="safeguarding_flag" class="select-none">
        <span class="font-medium">Safeguarding Concern</span>
        <p class="text-sm text-gray-500">Tick if the service user has an active safeguarding concern/alert.</p>
      </label>
    </div>
    @error('safeguarding_flag')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Infection Control Flag --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="infection_control_flag" value="0">
      <input id="infection_control_flag" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="infection_control_flag" value="1"
             @checked(old('infection_control_flag', (int)$su->infection_control_flag) == 1)
             class="mt-1">
      <label for="infection_control_flag" class="select-none">
        <span class="font-medium">Infection Control Flag</span>
        <p class="text-sm text-gray-500">Tick if special infection prevention/control measures are needed.</p>
      </label>
    </div>
    @error('infection_control_flag')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Smoking Status --}}
    <div>
      <label class="block text-sm mb-1">Smoking Status</label>
      <input type="text" name="smoking_status"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Smoker / Non-smoker / Ex-smoker"
             value="{{ old('smoking_status', $su->smoking_status) }}">
      @error('smoking_status')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Capacity Status --}}
    <div>
      <label class="block text-sm mb-1">Capacity Status</label>
      <input type="text" name="capacity_status"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Full / Partial / Lacks capacity"
             value="{{ old('capacity_status', $su->capacity_status) }}">
      @error('capacity_status')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Risks
    </button>
  </div>
</form>
