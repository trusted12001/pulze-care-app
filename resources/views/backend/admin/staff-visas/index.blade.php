@extends('layouts.admin')

@section('title','Visas / RTW')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">
      Visas / Right-to-Work — {{ $staffProfile->user->full_name ?? '—' }}
    </h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.visas.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Visa / RTW
    </a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Category</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Nationality</th>
            <th class="px-4 py-2">Visa #</th>
            <th class="px-4 py-2">BRP #</th>
            <th class="px-4 py-2">Issued</th>
            <th class="px-4 py-2">Expires</th>
            <th class="px-4 py-2">Hours Cap</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visas as $v)
            @php
              $badge = match($v->status) {
                'active' => 'bg-green-500',
                'expired' => 'bg-gray-500',
                'pending' => 'bg-amber-500',
                'revoked' => 'bg-red-500',
                default => 'bg-gray-400'
              };
              $cat = ucwords(str_replace('_',' ', $v->immigration_category ?? ''));
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $cat ?: '—' }}</td>
              <td class="px-4 py-2">
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">
                  {{ ucfirst($v->status) }}
                </span>
              </td>
              <td class="px-4 py-2">{{ $v->nationality ?? '—' }}</td>
              <td class="px-4 py-2">{{ $v->visa_number ?? '—' }}</td>
              <td class="px-4 py-2">{{ $v->brp_number ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($v->issued_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($v->expires_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ $v->weekly_hours_cap !== null ? $v->weekly_hours_cap : '—' }} {{ $v->term_time_only ? '(term-time)' : '' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.visas.edit', [$staffProfile, $v]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.visas.destroy', [$staffProfile, $v]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this visa record?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No visa/RTW records yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($visas, 'hasPages') && $visas->hasPages())
        <div class="mt-4">{{ $visas->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
