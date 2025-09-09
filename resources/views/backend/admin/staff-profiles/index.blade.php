@extends('layouts.admin')

@section('title','Staff Profile')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  {{-- Header --}}
  <div class="flex justify-between items-center mb-7">
    <a href="{{ route('backend.admin.staff-profiles.create') }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Create Staff Profile
    </a>
    <a href="{{ route('backend.admin.index') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
  </div>

  {{-- Feedback Messages (same pattern as Manage Staff) --}}
  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md shadow-sm">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md shadow-sm">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif


  {{-- Table card (match Manage Staff look/feel) --}}
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-semibold text-gray-800">Profiles</h3>
      <input type="text" id="profileSearch" placeholder="Search..."
             class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/3 bg-gray-50" />
    </div>

    <div class="overflow-x-auto">
      <table id="profilesTable" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Job Title</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">DBS</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
          @forelse($profiles as $p)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $p->user->full_name ?? '—' }}</td>
              <td class="px-4 py-2">{{ $p->user->email ?? '—' }}</td>
              <td class="px-4 py-2">{{ $p->job_title ?? '—' }}</td>
              <td class="px-4 py-2">
                @php
                  $status = $p->employment_status ?? 'unknown';
                  $badge  = match($status) {
                    'active'   => 'bg-green-500',
                    'on_leave' => 'bg-amber-500',
                    'inactive' => 'bg-gray-500',
                    default    => 'bg-gray-400',
                  };
                @endphp
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">
                  {{ ucfirst(str_replace('_',' ',$status)) }}
                </span>
              </td>
              <td class="px-4 py-2">{{ $p->dbs_number ? 'Yes' : 'No' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.show', $p) }}"
                   class="text-sm text-gray-600 hover:underline">View</a>
                <a href="{{ route('backend.admin.staff-profiles.edit', $p) }}"
                   class="text-sm text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.destroy', $p) }}"
                      method="POST" class="inline-block"
                      onsubmit="return confirm('Move this profile to recycle bin?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center px-4 py-6 text-gray-500">No staff profiles found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- Pagination (same renderer as Manage Staff) --}}
      @if(method_exists($profiles, 'hasPages') && $profiles->hasPages())
        <div class="mt-4">
          {{ $profiles->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>
  </div>

  {{-- View Trash link --}}
  <div class="mt-4">
    <a href="{{ route('backend.admin.staff-profiles.trashed') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 hover:underline">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
      </svg>
      View Trash
    </a>
  </div>

</div>

{{-- Client-side filter (like Manage Staff) --}}
<script>
  (function () {
    const input = document.getElementById('profileSearch');
    if (!input) return;

    input.addEventListener('keyup', function () {
      const term = this.value.toLowerCase();
      const rows = document.querySelectorAll('#profilesTable tbody tr');

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  })();
</script>
@endsection
