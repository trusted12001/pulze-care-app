@extends('layouts.admin')

@section('title','Employment Checks')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">
      Employment Checks — {{ $staffProfile->user->full_name ?? '—' }}
    </h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  {{-- Optional tabs --}}
  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.employment-checks.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Employment Check
    </a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Result</th>
            <th class="px-4 py-2">Ref</th>
            <th class="px-4 py-2">Issued</th>
            <th class="px-4 py-2">Expires</th>
            <th class="px-4 py-2">Verified By</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($checks as $c)
            @php
              $badge = match($c->result) {
                'pass' => 'bg-green-500', 'fail' => 'bg-red-500',
                'pending' => 'bg-amber-500', default => 'bg-gray-400'
              };
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ Str::headline(str_replace('_',' ', $c->check_type)) }}</td>
              <td class="px-4 py-2">
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">
                  {{ ucfirst($c->result) }}
                </span>
              </td>
              <td class="px-4 py-2">{{ $c->reference_no ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($c->issued_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($c->expires_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">
                {{ optional($c->checkedBy)->full_name ?? '—' }}
              </td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.employment-checks.edit', [$staffProfile, $c]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.employment-checks.destroy', [$staffProfile, $c]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this check?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No employment checks yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($checks, 'hasPages') && $checks->hasPages())
        <div class="mt-4">{{ $checks->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
