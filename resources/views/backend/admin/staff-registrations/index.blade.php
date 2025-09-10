@extends('layouts.admin')

@section('title','Registrations')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Registrations — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.registrations.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Registration
    </a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Body</th>
            <th class="px-4 py-2">PIN</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">First Reg.</th>
            <th class="px-4 py-2">Expires</th>
            <th class="px-4 py-2">Revalidation</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($registrations as $r)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $r->body }}</td>
              <td class="px-4 py-2">{{ $r->pin_number ?? '—' }}</td>
              <td class="px-4 py-2">
                @php
                  $badge = match($r->status) {
                    'active' => 'bg-green-500', 'lapsed' => 'bg-gray-500',
                    'suspended' => 'bg-red-500', 'pending' => 'bg-amber-500', default => 'bg-gray-400'
                  };
                @endphp
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">{{ ucfirst($r->status) }}</span>
              </td>
              <td class="px-4 py-2">{{ optional($r->first_registered_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($r->expires_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($r->revalidation_due_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.registrations.edit', [$staffProfile, $r]) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.registrations.destroy', [$staffProfile, $r]) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this registration?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No registrations yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($registrations, 'hasPages') && $registrations->hasPages())
        <div class="mt-4">{{ $registrations->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
