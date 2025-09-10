@php
  $isEdit = isset($payroll) && $payroll->exists;
  $loanPlans = ['none'=>'None','plan1'=>'Plan 1','plan2'=>'Plan 2','plan4'=>'Plan 4','plan5'=>'Plan 5'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">NI Number</label>
    <input type="text" name="ni_number"
           value="{{ old('ni_number') }}"
           placeholder="{{ $isEdit ? 'Leave blank to keep unchanged' : '' }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" {{ $isEdit ? '' : 'required' }}>
    @error('ni_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Tax Code</label>
    <input type="text" name="tax_code" value="{{ old('tax_code', $payroll->tax_code ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('tax_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Starter Declaration</label>
    @php $sd = old('starter_declaration', $payroll->starter_declaration ?? ''); @endphp
    <select name="starter_declaration" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
      <option value="">— Select —</option>
      <option value="a" @selected($sd==='a')>A</option>
      <option value="b" @selected($sd==='b')>B</option>
      <option value="c" @selected($sd==='c')>C</option>
    </select>
    @error('starter_declaration') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Student Loan Plan</label>
    @php $sl = old('student_loan_plan', $payroll->student_loan_plan ?? 'none'); @endphp
    <select name="student_loan_plan" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($loanPlans as $k => $lbl)
        <option value="{{ $k }}" @selected($sl===$k)>{{ $lbl }}</option>
      @endforeach
    </select>
    @error('student_loan_plan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="flex items-center gap-2">
    @php $pgl = (bool)old('postgrad_loan', $payroll->postgrad_loan ?? false); @endphp
    <input type="hidden" name="postgrad_loan" value="0">
    <input id="postgrad_loan" type="checkbox" name="postgrad_loan" value="1" class="rounded" @checked($pgl)>
    <label for="postgrad_loan" class="font-medium text-gray-800">Postgraduate Loan</label>
    @error('postgrad_loan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Payroll Number</label>
    <input type="text" name="payroll_number" value="{{ old('payroll_number', $payroll->payroll_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('payroll_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.payroll.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
