@extends('layouts.admin')

@section('title','Location Setup')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">Locations</h2>
    <a href="{{ route('backend.admin.index') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
  </div>

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md shadow-sm">
      {{ session('success') }}
    </div>
  @endif

    {{-- Create button --}}
    <div class="mb-4">
        <a href="{{ route('backend.admin.locations.create') }}"
        class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
        Add New Location
        </a>
    </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
      <h3 class="text-2xl font-semibold text-gray-800">Locations</h3>

      <input type="text" id="locSearch" placeholder="Search..."
             class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/3 bg-gray-50" />
    </div>

    <div class="overflow-x-auto">
      <table id="locTable" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Address</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Radius (m)</th>
            <th class="px-4 py-2">Service Users</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
          @forelse($locations as $loc)
            @php
              $badge = $loc->status === 'active' ? 'bg-green-500' : 'bg-gray-500';
              $addr = trim(implode(', ', array_filter([
                $loc->address_line1, $loc->city, $loc->postcode
              ])));
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $loop->iteration + ($locations->firstItem() - 1) }}</td>
              <td class="px-4 py-2 font-medium">{{ $loc->name }}</td>
              <td class="px-4 py-2">{{ ucfirst(str_replace('_',' ',$loc->type)) }}</td>
              <td class="px-4 py-2">{{ $addr ?: '—' }}</td>
              <td class="px-4 py-2">
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">
                  {{ ucfirst($loc->status) }}
                </span>
              </td>
              <td class="px-4 py-2">{{ $loc->geofence_radius_m ?? '—' }}</td>
              <td class="px-4 py-2">{{ $loc->service_users_count ?? 0 }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.locations.show', $loc) }}" class="text-sm text-gray-600 hover:underline">View</a>
                <a href="{{ route('backend.admin.locations.edit', $loc) }}" class="text-sm text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.locations.destroy', $loc) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Move this location to recycle bin?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center px-4 py-6 text-gray-500">No locations found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($locations->hasPages())
        <div class="mt-4">
          {{ $locations->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>

  </div>
      <div class="mt-4">
      <a href="{{ route('backend.admin.locations.trashed') }}"
         class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 hover:underline">
        {{-- small trash icon --}}
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"></path>
        </svg>
        View Trash
      </a>
    </div>

</div>

<script>
  (function () {
    const input = document.getElementById('locSearch');
    if (!input) return;
    input.addEventListener('keyup', function () {
      const term = this.value.toLowerCase();
      const rows = document.querySelectorAll('#locTable tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  })();
</script>
@endsection
