@php $isEdit = isset($entitlement) && $entitlement->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Period Start</label>
    <input type="date" name="period_start"
           value="{{ old('period_start', optional($entitlement->period_start ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('period_start') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Period End</label>
    <input type="date" name="period_end"
           value="{{ old('period_end', optional($entitlement->period_end ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('period_end') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Annual Leave Days</label>
    <input type="number" step="0.01" name="annual_leave_days"
           value="{{ old('annual_leave_days', $entitlement->annual_leave_days ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
    @error('annual_leave_days') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block mb-1 font-medium text-gray-800">Sick Pay Scheme (optional)</label>
    <input type="text" name="sick_pay_scheme"
           value="{{ old('sick_pay_scheme', $entitlement->sick_pay_scheme ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('sick_pay_scheme') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
