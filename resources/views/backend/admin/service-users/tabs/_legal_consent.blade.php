<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'legal_consent']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- Consent Obtained At (datetime) --}}
    <div>
      <label class="block text-sm mb-1">Consent Obtained At</label>
      <input type="datetime-local"
             name="consent_obtained_at"
             class="w-full border rounded px-3 py-2"
             value="{{ old('consent_obtained_at', optional($su->consent_obtained_at)->format('Y-m-d\TH:i')) }}">
      @error('consent_obtained_at')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      <p class="text-xs text-gray-500 mt-1">Record when consent was formally obtained (best interests if applicable).</p>
    </div>

    {{-- DNACPR Status --}}
    <div>
      <label class="block text-sm mb-1">DNACPR Status</label>
      <input type="text"
             name="dnacpr_status"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., In place / Not in place / Not applicable"
             value="{{ old('dnacpr_status', $su->dnacpr_status) }}">
      @error('dnacpr_status')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- DNACPR Review Date --}}
    <div>
      <label class="block text-sm mb-1">DNACPR Review Date</label>
      <input type="date"
             name="dnacpr_review_date"
             class="w-full border rounded px-3 py-2"
             value="{{ old('dnacpr_review_date', optional($su->dnacpr_review_date)->format('Y-m-d')) }}">
      @error('dnacpr_review_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- DoLS in place --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="dols_in_place" value="0">
      <input id="dols_in_place" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="dols_in_place" value="1"
             @checked(old('dols_in_place', (int)$su->dols_in_place) == 1)
             class="mt-1">
      <label for="dols_in_place" class="select-none">
        <span class="font-medium">DoLS in Place</span>
        <p class="text-sm text-gray-500">Tick if Deprivation of Liberty Safeguards authorization is active.</p>
      </label>
    </div>
    @error('dols_in_place')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- DoLS Approval Date --}}
    <div>
      <label class="block text-sm mb-1">DoLS Approval Date</label>
      <input type="date"
             name="dols_approval_date"
             class="w-full border rounded px-3 py-2"
             value="{{ old('dols_approval_date', optional($su->dols_approval_date)->format('Y-m-d')) }}">
      @error('dols_approval_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- LPA Health & Welfare --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="lpa_health_welfare" value="0">
      <input id="lpa_health_welfare" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="lpa_health_welfare" value="1"
             @checked(old('lpa_health_welfare', (int)$su->lpa_health_welfare) == 1)
             class="mt-1">
      <label for="lpa_health_welfare" class="select-none">
        <span class="font-medium">LPA: Health & Welfare</span>
        <p class="text-sm text-gray-500">Tick if a registered Lasting Power of Attorney for Health & Welfare exists.</p>
      </label>
    </div>
    @error('lpa_health_welfare')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- LPA Finance & Property --}}
    <div class="flex items-start gap-2">
      <input type="hidden" name="lpa_finance_property" value="0">
      <input id="lpa_finance_property" class="dark:bg-gray-300 dark:border-gray-600" type="checkbox" name="lpa_finance_property" value="1"
             @checked(old('lpa_finance_property', (int)$su->lpa_finance_property) == 1)
             class="mt-1">
      <label for="lpa_finance_property" class="select-none">
        <span class="font-medium">LPA: Finance & Property</span>
        <p class="text-sm text-gray-500">Tick if a registered LPA for Property & Financial Affairs exists.</p>
      </label>
    </div>
    @error('lpa_finance_property')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

    {{-- Advanced Decision / ADRT Note --}}
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Advanced Decision Note</label>
      <textarea name="advanced_decision_note" rows="5"
                class="w-full border rounded px-3 py-2"
                placeholder="Advance decision / ADRT summary, scope, dates, and where to find the signed document.">{{ old('advanced_decision_note', $su->advanced_decision_note) }}</textarea>
      @error('advanced_decision_note')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Legal & Consent
    </button>
  </div>
</form>
