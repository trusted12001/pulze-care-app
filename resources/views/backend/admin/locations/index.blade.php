@extends('layouts.admin')

@section('title', 'Location Setup')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y') : '—';
  @endphp

  <div class="min-h-screen p-3 sm:p-4 lg:p-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Location Setup</h1>
          <p class="text-sm sm:text-base text-gray-600">Manage and view all locations</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
          <a href="{{ route('backend.admin.locations.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-plus-circle"></i>
            <span>Add New Location</span>
          </a>
          <a href="{{ route('backend.admin.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-arrow-left"></i>
            <span>Back to Dashboard</span>
          </a>
        </div>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('success') }}</span>
        </div>
      </div>
    @endif

    {{-- Locations List Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i class="ph ph-map-pin text-purple-600"></i>
            <span>All Locations</span>
            <span class="text-sm font-normal text-gray-500">({{ $locations->total() }})</span>
          </h3>
          <div class="relative w-full sm:w-auto sm:min-w-[250px]">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="locSearch" placeholder="Search locations..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-white text-sm sm:text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" />
          </div>
        </div>
      </div>

      {{-- Desktop Table View --}}
      <div class="hidden md:block overflow-x-auto">
        <table id="locTable" class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service
                Users</th>
              <th class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($locations as $loc)
              @php
                $badge = $loc->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                $addr = trim(implode(', ', array_filter([$loc->address_line1, $loc->city, $loc->postcode])));
              @endphp
              <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $loop->iteration + ($locations->firstItem() - 1) }}</td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white">
                      <i class="ph ph-map-pin text-sm sm:text-base"></i>
                    </div>
                    <div class="text-sm sm:text-base font-medium text-gray-900">{{ $loc->name }}</div>
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full text-xs sm:text-sm font-medium">
                    {{ ucfirst(str_replace('_', ' ', $loc->type)) }}
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-4">
                  <div class="text-sm sm:text-base text-gray-900 max-w-xs truncate">{{ $addr ?: '—' }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $loc->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                    {{ ucfirst($loc->status) }}
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <span class="text-sm sm:text-base text-gray-900">{{ $loc->service_users_count ?? 0 }}</span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('backend.admin.locations.show', $loc) }}"
                      class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                      title="View">
                      <i class="ph ph-eye"></i>
                      <span class="hidden lg:inline">View</span>
                    </a>
                    <a href="{{ route('backend.admin.locations.edit', $loc) }}"
                      class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200"
                      title="Edit">
                      <i class="ph ph-pencil-simple"></i>
                      <span class="hidden lg:inline">Edit</span>
                    </a>
                    <button type="button" onclick="showDeleteModal({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                      class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                      title="Delete">
                      <i class="ph ph-trash"></i>
                      <span class="hidden lg:inline">Delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 sm:px-6 py-12 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <i class="ph ph-map-pin text-4xl text-gray-300"></i>
                    <p class="text-gray-500 text-sm sm:text-base">No locations found.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile Card View --}}
      <div class="md:hidden divide-y divide-gray-200">
        @forelse($locations as $loc)
          @php
            $badge = $loc->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
            $addr = trim(implode(', ', array_filter([$loc->address_line1, $loc->city, $loc->postcode])));
          @endphp
          <div class="p-4 hover:bg-gray-50 transition-colors duration-150 location-card">
            <div class="flex items-start gap-4">
              <div
                class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white flex-shrink-0">
                <i class="ph ph-map-pin text-xl"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-2">
                  <div class="min-w-0 flex-1">
                    <h4 class="text-base font-semibold text-gray-900 truncate">{{ $loc->name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5 break-all">{{ $addr ?: '—' }}</p>
                  </div>
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $loc->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                    {{ ucfirst($loc->status) }}
                  </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                  <span
                    class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                    {{ ucfirst(str_replace('_', ' ', $loc->type)) }}
                  </span>
                  <span class="text-xs text-gray-600">
                    <i class="ph ph-users"></i>
                    {{ $loc->service_users_count ?? 0 }} Service Users
                  </span>
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                  <a href="{{ route('backend.admin.locations.show', $loc) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors text-sm font-medium">
                    <i class="ph ph-eye"></i>
                    View
                  </a>
                  <a href="{{ route('backend.admin.locations.edit', $loc) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 active:bg-indigo-200 transition-colors text-sm font-medium">
                    <i class="ph ph-pencil-simple"></i>
                    Edit
                  </a>
                  <button type="button" onclick="showDeleteModal({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition-colors text-sm font-medium">
                    <i class="ph ph-trash"></i>
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="p-12 text-center">
            <div class="flex flex-col items-center gap-3">
              <i class="ph ph-map-pin text-4xl text-gray-300"></i>
              <p class="text-gray-500 text-sm sm:text-base">No locations found.</p>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($locations->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
          {{ $locations->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>

    {{-- Trash Link --}}
    <div class="mt-4 sm:mt-6">
      <a href="{{ route('backend.admin.locations.trashed') }}"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm sm:text-base text-gray-600 hover:text-red-600 hover:underline transition-colors duration-200">
        <i class="ph ph-trash"></i>
        <span>View Trash</span>
      </a>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
      aria-modal="true">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideDeleteModal()"></div>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="ph ph-warning text-red-600 text-2xl"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">Delete Location</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Are you sure you want to move this location to recycle bin?</p>
                  <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-900" id="deleteLocationName"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
              <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <i class="ph ph-trash"></i>
                Delete Location
              </button>
              <button type="button" onclick="hideDeleteModal()"
                class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <i class="ph ph-x"></i>
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Search Script --}}
    <script>
      function showDeleteModal(locationId, locationName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameElement = document.getElementById('deleteLocationName');

        form.action = '{{ route("backend.admin.locations.destroy", ":id") }}'.replace(':id', locationId);
        nameElement.textContent = locationName;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          hideDeleteModal();
        }
      });

      document.getElementById('locSearch').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#locTable tbody tr, .location-card');

        rows.forEach(row => {
          const rowText = row.innerText.toLowerCase();
          row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
      });
    </script>
@endsection