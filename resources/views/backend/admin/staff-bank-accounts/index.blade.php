@extends('layouts.admin')

@section('title','Bank Accounts')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">
      Bank Accounts — {{ $staffProfile->user->full_name ?? '—' }}
    </h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.bank-accounts.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Bank Account
    </a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Account Holder</th>
            <th class="px-4 py-2">Sort Code</th>
            <th class="px-4 py-2">Account Number</th>
            <th class="px-4 py-2">Building Society Roll</th>
            <th class="px-4 py-2">Created</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bankAccounts as $acc)
            @php
              // Mask values to avoid exposing full details in the UI
              $scDigits = preg_replace('/\D+/', '', (string)($acc->sort_code ?? ''));
              $maskedSort = $scDigits ? '••-••-' . substr($scDigits, -2) : '—';
              $acctDigits = preg_replace('/\D+/', '', (string)($acc->account_number ?? ''));
              $maskedAcct = $acctDigits ? str_repeat('•', max(strlen($acctDigits)-2, 0)) . substr($acctDigits, -2) : '—';
              $bsr = $acc->building_society_roll ? 'Yes' : '—';
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $acc->account_holder }}</td>
              <td class="px-4 py-2">{{ $maskedSort }}</td>
              <td class="px-4 py-2">{{ $maskedAcct }}</td>
              <td class="px-4 py-2">{{ $bsr }}</td>
              <td class="px-4 py-2">{{ optional($acc->created_at)->format('d M Y') }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.bank-accounts.edit', [$staffProfile, $acc]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.bank-accounts.destroy', [$staffProfile, $acc]) }}"
                      method="POST" class="inline-block"
                      onsubmit="return confirm('Delete this bank account?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No bank accounts added.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($bankAccounts, 'hasPages') && $bankAccounts->hasPages())
        <div class="mt-4">{{ $bankAccounts->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
