@extends('layouts.admin')
@section('title','Leave')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Leave — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4 flex items-center justify-between gap-3">
    <div class="space-x-2">
      <a href="{{ route('backend.admin.staff-profiles.leave-records.create', $staffProfile) }}"
         class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Record Leave</a>
      <a href="{{ route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile) }}"
         class="px-4 py-2 border rounded hover:bg-gray-50">Entitlements</a>
      <a href="{{ route('backend.admin.staff-profiles.availability.index', $staffProfile) }}"
         class="px-4 py-2 border rounded hover:bg-gray-50">Availability</a>
    </div>

    <form method="GET" class="flex items-center gap-2">
      <select name="type" class="border border-gray-300 px-3 py-2 rounded bg-gray-50">
        <option value="">All types</option>
        @foreach(['annual','sick','unpaid','maternity','paternity','compassionate','study','other'] as $t)
          <option value="{{ $t }}" {{ ($type ?? '')===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search reason…"
             class="border border-gray-300 px-3 py-2 rounded bg-gray-50" />
      <button class="px-3 py-2 border rounded hover:bg-gray-50">Go</button>
    </form>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Start</th>
            <th class="px-4 py-2">End</th>
            <th class="px-4 py-2">Hours</th>
            <th class="px-4 py-2">Reason</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ ucfirst($r->type) }}</td>
              <td class="px-4 py-2">{{ optional($r->start_date)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($r->end_date)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ $r->hours ?? '—' }}</td>
              <td class="px-4 py-2">{{ $r->reason ? Str::limit($r->reason, 80) : '—' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.leave-records.edit', [$staffProfile, $r]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.leave-records.destroy', [$staffProfile, $r]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this leave record?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No leave records yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($records, 'hasPages') && $records->hasPages())
        <div class="mt-4">{{ $records->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
