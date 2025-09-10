@php $isEdit = isset($contract) && $contract->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Contract Type</label>
    @php $ct = old('contract_type', $contract->contract_type ?? 'permanent'); @endphp
    <select name="contract_type" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach(['permanent','fixed_term','bank','casual','agency'] as $opt)
        <option value="{{ $opt }}" @selected($ct===$opt)>{{ ucwords(str_replace('_',' ',$opt)) }}</option>
      @endforeach
    </select>
    @error('contract_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Contract Ref</label>
    <input type="text" name="contract_ref" value="{{ old('contract_ref', $contract->contract_ref ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('contract_ref') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Start Date</label>
    <input type="date" name="start_date"
           value="{{ old('start_date', optional($contract->start_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">End Date</label>
    <input type="date" name="end_date"
           value="{{ old('end_date', optional($contract->end_date ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Hours / Week</label>
    <input type="number" step="0.01" min="0" max="99.99" name="hours_per_week"
           value="{{ old('hours_per_week', $contract->hours_per_week ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('hours_per_week') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">WTD 48h Opt-Out</label>
    <label class="inline-flex items-center space-x-2">
      <input type="checkbox" name="wtd_opt_out" value="1"
             class="rounded" {{ old('wtd_opt_out', $contract->wtd_opt_out ?? false) ? 'checked' : '' }}>
      <span class="text-sm text-gray-700">Opted out</span>
    </label>
    @error('wtd_opt_out') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Job Grade / Band</label>
    <input type="text" name="job_grade_band" value="{{ old('job_grade_band', $contract->job_grade_band ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('job_grade_band') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Pay Scale</label>
    <input type="text" name="pay_scale" value="{{ old('pay_scale', $contract->pay_scale ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('pay_scale') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">FTE Salary (£)</label>
    <input type="number" step="0.01" min="0" name="fte_salary"
           value="{{ old('fte_salary', $contract->fte_salary ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('fte_salary') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Hourly Rate (£)</label>
    <input type="number" step="0.01" min="0" name="hourly_rate"
           value="{{ old('hourly_rate', $contract->hourly_rate ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('hourly_rate') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Cost Centre</label>
    <input type="text" name="cost_centre" value="{{ old('cost_centre', $contract->cost_centre ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('cost_centre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $contract->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.contracts.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
