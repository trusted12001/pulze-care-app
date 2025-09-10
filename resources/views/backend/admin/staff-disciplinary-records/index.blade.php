@extends('layouts.admin')

@section('title','Disciplinary')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Disciplinary — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4 flex items-center justify-between gap-3">
    <a href="{{ route('backend.admin.staff-profiles.disciplinary.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Add Record</a>

    <form method="GET" class="flex items-center gap-2">
      <select name="type" class="border border-gray-300 px-3 py-2 rounded bg-gray-50">
        <option value="">All types</option>
        @foreach(['informal','formal','warning','dismissal'] as $t)
          <option value="{{ $t }}" {{ ($type ?? '')===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search summary/outcome…"
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
            <th class="px-4 py-2">Opened</th>
            <th class="px-4 py-2">Closed</th>
            <th class="px-4 py-2">Summary</th>
            <th class="px-4 py-2">Outcome</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ ucfirst($it->type) }}</td>
              <td class="px-4 py-2">{{ optional($it->opened_at)->format('d M Y') }}</td>
              <td class="px-4 py-2">{{ optional($it->closed_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($it->summary, 70) }}</td>
              <td class="px-4 py-2">{{ $it->outcome ? \Illuminate\Support\Str::limit($it->outcome, 50) : '—' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.disciplinary.edit', [$staffProfile, $it]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.disciplinary.destroy', [$staffProfile, $it]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this record?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No disciplinary records yet.</td></tr>
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
