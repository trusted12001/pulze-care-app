@extends('layouts.admin')

@section('title','Staff Profiles — Trash')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  {{-- Header --}}
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">Staff Profiles — Trash</h2>
    <a href="{{ route('backend.admin.staff-profiles.index') }}" class="text-blue-600 hover:underline">← Back to List</a>
  </div>

  {{-- Table card --}}
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-semibold text-gray-800">Deleted Staff Profiles</h3>
      <input type="text" id="trashSearch" placeholder="Search..."
             class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/3 bg-gray-50" />
    </div>

    <div class="overflow-x-auto">
      <table id="trashTable" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Job Title</th>
            <th class="px-4 py-2">Deleted At</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
          @forelse($profiles as $p)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $loop->iteration + ($profiles->firstItem() - 1) }}</td>
              <td class="px-4 py-2">{{ $p->user->name ?? '—' }}</td>
              <td class="px-4 py-2">{{ $p->user->email ?? '—' }}</td>
              <td class="px-4 py-2">{{ $p->job_title ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($p->deleted_at)->format('d M Y H:i') }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <form action="{{ route('backend.admin.staff-profiles.restore', $p->id) }}"
                      method="POST" class="inline-block">
                  @csrf
                  <button class="text-sm text-green-700 hover:underline">Restore</button>
                </form>

                <form action="{{ route('backend.admin.staff-profiles.forceDelete', $p->id) }}"
                      method="POST" class="inline-block"
                      onsubmit="return confirm('Permanently delete this staff profile? This cannot be undone.');">
                  @csrf @method('DELETE')
                  <button class="text-sm text-red-700 hover:underline">Delete Permanently</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center px-4 py-6 text-gray-500">Trash is empty.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- Pagination --}}
      @if(method_exists($profiles, 'hasPages') && $profiles->hasPages())
        <div class="mt-4">
          {{ $profiles->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>
  </div>
</div>

{{-- Client-side search --}}
<script>
  (function () {
    const input = document.getElementById('trashSearch');
    if (!input) return;
    input.addEventListener('keyup', function () {
      const term = this.value.toLowerCase();
      const rows = document.querySelectorAll('#trashTable tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  })();
</script>
@endsection
