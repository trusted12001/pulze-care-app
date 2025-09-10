@extends('layouts.admin')

@section('title','Contracts')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Contracts — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.contracts.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Create Contract
    </a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])


  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Start</th>
            <th class="px-4 py-2">End</th>
            <th class="px-4 py-2">Hours</th>
            <th class="px-4 py-2">FTE (£)</th>
            <th class="px-4 py-2">Hourly (£)</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contracts as $c)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ ucwords(str_replace('_',' ', $c->contract_type)) }}</td>
              <td class="px-4 py-2">{{ optional($c->start_date)->format('d M Y') }}</td>
              <td class="px-4 py-2">{{ optional($c->end_date)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ $c->hours_per_week ?? '—' }}</td>
              <td class="px-4 py-2">{{ $c->fte_salary ? number_format($c->fte_salary, 2) : '—' }}</td>
              <td class="px-4 py-2">{{ $c->hourly_rate ? number_format($c->hourly_rate, 2) : '—' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.contracts.edit', [$staffProfile, $c]) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.contracts.destroy', [$staffProfile, $c]) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this contract?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No contracts yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($contracts, 'hasPages') && $contracts->hasPages())
        <div class="mt-4">{{ $contracts->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
