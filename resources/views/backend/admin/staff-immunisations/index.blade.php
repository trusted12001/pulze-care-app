@extends('layouts.admin')

@section('title','Immunisations')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">
      Immunisations — {{ $staffProfile->user->full_name ?? '—' }}
    </h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4 flex items-center justify-between gap-3">
    <a href="{{ route('backend.admin.staff-profiles.immunisations.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Immunisation
    </a>

    <form method="GET" class="flex items-center gap-2">
      <select name="v" class="border border-gray-300 px-3 py-2 rounded bg-gray-50">
        <option value="">All vaccines</option>
        @foreach($vaccines as $vx)
          <option value="{{ $vx }}" {{ ($filterV ?? '')===$vx ? 'selected' : '' }}>{{ $vx }}</option>
        @endforeach
      </select>
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search dose/notes…"
             class="border border-gray-300 px-3 py-2 rounded bg-gray-50" />
      <button class="px-3 py-2 border rounded hover:bg-gray-50">Go</button>
    </form>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Vaccine</th>
            <th class="px-4 py-2">Dose</th>
            <th class="px-4 py-2">Administered</th>
            <th class="px-4 py-2">Evidence</th>
            <th class="px-4 py-2">Notes</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            @php
              $notes = $it->notes ? (mb_strlen($it->notes) > 90 ? mb_substr($it->notes,0,90).'…' : $it->notes) : '—';
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $it->vaccine }}</td>
              <td class="px-4 py-2">{{ $it->dose ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($it->administered_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ $it->evidence_file_id ? 'Yes' : 'No' }}</td>
              <td class="px-4 py-2">{{ $notes }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.immunisations.edit', [$staffProfile, $it]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.immunisations.destroy', [$staffProfile, $it]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this immunisation record?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No immunisation records yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($items, 'hasPages') && $items->hasPages())
        <div class="mt-4">{{ $items->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
