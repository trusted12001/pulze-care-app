@extends('layouts.admin')

@section('title','Payroll')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Payroll — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    @if(!$payroll)
      <p class="mb-4 text-gray-700">No payroll details yet.</p>
      <a href="{{ route('backend.admin.staff-profiles.payroll.create', $staffProfile) }}"
         class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
         Add Payroll
      </a>
    @else
      @php
        $maskedNi = $payroll->ni_number ? \Illuminate\Support\Str::mask($payroll->ni_number, '*', 0, max(0, strlen($payroll->ni_number)-2)) : '—';
      @endphp
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h4 class="text-sm text-gray-500">NI Number</h4>
          <p class="text-gray-800 font-medium">{{ $maskedNi }}</p>
        </div>
        <div>
          <h4 class="text-sm text-gray-500">Tax Code</h4>
          <p class="text-gray-800">{{ $payroll->tax_code ?? '—' }}</p>
        </div>
        <div>
          <h4 class="text-sm text-gray-500">Starter Declaration</h4>
          <p class="text-gray-800">{{ strtoupper($payroll->starter_declaration ?? '—') }}</p>
        </div>
        <div>
          <h4 class="text-sm text-gray-500">Student Loan Plan</h4>
          <p class="text-gray-800">{{ strtoupper($payroll->student_loan_plan) }}</p>
        </div>
        <div>
          <h4 class="text-sm text-gray-500">Postgrad Loan</h4>
          <p class="text-gray-800">{{ $payroll->postgrad_loan ? 'Yes' : 'No' }}</p>
        </div>
        <div>
          <h4 class="text-sm text-gray-500">Payroll Number</h4>
          <p class="text-gray-800">{{ $payroll->payroll_number ?? '—' }}</p>
        </div>
      </div>

      <div class="mt-6 text-right space-x-2">
        <a href="{{ route('backend.admin.staff-profiles.payroll.edit', [$staffProfile, $payroll]) }}"
           class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Edit</a>
        <form action="{{ route('backend.admin.staff-profiles.payroll.destroy', [$staffProfile, $payroll]) }}"
              method="POST" class="inline-block"
              onsubmit="return confirm('Remove payroll details?')">
          @csrf @method('DELETE')
          <button class="text-red-600 hover:underline">Delete</button>
        </form>
      </div>
    @endif
  </div>
</div>
@endsection
