<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'clinical_flags']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- Behaviour Support Plan --}}
    <div class="flex items-start gap-2">
      {{-- Hidden ensures a value is always sent even when unchecked --}}
      <input type="hidden" name="behaviour_support_plan" value="0">
      <input id="behaviour_support_plan" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="behaviour_support_plan" value="1"
             @checked(old('behaviour_support_plan', (int)$su->behaviour_support_plan) == 1)
             class="mt-1">
      <label for="behaviour_support_plan" class="select-none">
        <span class="font-medium">Behaviour Support Plan</span>
        <p class="text-sm text-gray-500">Tick if a BSP is in place (or required) for behaviours of concern.</p>
      </label>
    </div>
    @error('behaviour_support_plan')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Seizure Care Plan --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="seizure_care_plan" value="0">
      <input id="seizure_care_plan" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="seizure_care_plan" value="1"
             @checked(old('seizure_care_plan', (int)$su->seizure_care_plan) == 1)
             class="mt-1">
      <label for="seizure_care_plan" class="select-none">
        <span class="font-medium">Seizure Care Plan</span>
        <p class="text-sm text-gray-500">Tick if a seizure/epilepsy plan is in place (incl. rescue meds protocol).</p>
      </label>
    </div>
    @error('seizure_care_plan')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Diabetes Care Plan --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="diabetes_care_plan" value="0">
      <input id="diabetes_care_plan" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="diabetes_care_plan" value="1"
             @checked(old('diabetes_care_plan', (int)$su->diabetes_care_plan) == 1)
             class="mt-1">
      <label for="diabetes_care_plan" class="select-none">
        <span class="font-medium">Diabetes Care Plan</span>
        <p class="text-sm text-gray-500">Tick if there is a diabetes plan (diet, monitoring, hypo/hyper response).</p>
      </label>
    </div>
    @error('diabetes_care_plan')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Oxygen Therapy --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="oxygen_therapy" value="0">
      <input id="oxygen_therapy" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="oxygen_therapy" value="1"
             @checked(old('oxygen_therapy', (int)$su->oxygen_therapy) == 1)
             class="mt-1">
      <label for="oxygen_therapy" class="select-none">
        <span class="font-medium">Oxygen Therapy</span>
        <p class="text-sm text-gray-500">Tick if on oxygen (long-term or PRN) — ensure safety checks documented.</p>
      </label>
    </div>
    @error('oxygen_therapy')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Clinical Flags
    </button>
  </div>
</form>
