@extends('layouts.admin')

@section('title', 'Service Users — Trash')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y') : '—';
    $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y, h:i A') : '—';
  @endphp

  <div class="min-h-screen p-3 sm:p-4 lg:p-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-2">
            <i class="ph ph-trash text-red-600"></i>
            <span>Trashed Service Users</span>
          </h1>
          <p class="text-sm sm:text-base text-gray-600">View and manage deleted service users</p>
        </div>
        <a href="{{ route('backend.admin.service-users.index') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to List</span>
        </a>
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

    @if($serviceUsers->count())
      {{-- Service Users List Section --}}
      <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Table Header --}}
        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-trash text-red-600"></i>
              <span>Deleted Service Users</span>
              <span class="text-sm font-normal text-gray-500">({{ $serviceUsers->total() }})</span>
            </h3>
            <div class="relative w-full sm:w-auto sm:min-w-[250px]">
              <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <input type="text" id="trashSearch" placeholder="Search trashed service users..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-white text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" />
            </div>
          </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
          <table id="trashedServiceUsersTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name
                </th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">DOB
                </th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deleted
                </th>
                <th class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($serviceUsers as $su)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $loop->iteration + ($serviceUsers->currentPage() - 1) * $serviceUsers->perPage() }}
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white font-semibold text-sm sm:text-base">
                        {{ strtoupper(substr($su->first_name ?? 'U', 0, 1)) }}
                      </div>
                      <div>
                        <div class="text-sm sm:text-base font-medium text-gray-900">
                          {{ $su->full_name }}
                        </div>
                        <div class="text-xs text-gray-500">Deleted {{ $su->deleted_at->diffForHumans() }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 sm:px-6 py-4">
                    <div class="text-sm text-gray-900">{{ optional($su->date_of_birth)->format('d M Y') ?: '—' }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $fmtDateTime($su->deleted_at) }}</div>
                    <div class="text-xs text-gray-500">{{ $su->deleted_at->diffForHumans() }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                      <button type="button" onclick="showRestoreModal({{ $su->id }}, '{{ addslashes($su->full_name) }}')"
                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                        title="Restore">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        <span class="hidden lg:inline">Restore</span>
                      </button>
                      <button type="button" onclick="showForceDeleteModal({{ $su->id }}, '{{ addslashes($su->full_name) }}')"
                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                        title="Permanently Delete">
                        <i class="ph ph-trash"></i>
                        <span class="hidden lg:inline">Delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-200">
          @foreach($serviceUsers as $su)
            <div class="p-4 hover:bg-gray-50 transition-colors duration-150 trashed-service-user-card">
              <div class="flex items-start gap-4">
                <div
                  class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                  {{ strtoupper(substr($su->first_name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-base font-semibold text-gray-900 truncate">
                        {{ $su->full_name }}
                      </h4>
                      <p class="text-xs text-gray-500 mt-1">
                        DOB: {{ optional($su->date_of_birth)->format('d M Y') ?: '—' }}
                      </p>
                    </div>
                  </div>
                  <div class="mb-3">
                    <p class="text-xs text-gray-600">
                      <i class="ph ph-calendar"></i>
                      Deleted {{ $fmtDateTime($su->deleted_at) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $su->deleted_at->diffForHumans() }}</p>
                  </div>
                  <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="showRestoreModal({{ $su->id }}, '{{ addslashes($su->full_name) }}')"
                      class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 active:bg-green-200 transition-colors text-sm font-medium">
                      <i class="ph ph-arrow-counter-clockwise"></i>
                      Restore
                    </button>
                    <button type="button" onclick="showForceDeleteModal({{ $su->id }}, '{{ addslashes($su->full_name) }}')"
                      class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition-colors text-sm font-medium">
                      <i class="ph ph-trash"></i>
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Pagination --}}
        @if($serviceUsers->hasPages())
          <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $serviceUsers->links('vendor.pagination.tailwind') }}
          </div>
        @endif
      </div>
    @else
      {{-- Empty State --}}
      <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-12 text-center">
          <div class="flex flex-col items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
              <i class="ph ph-trash text-3xl text-gray-400"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-1">No Trashed Service Users</h3>
              <p class="text-sm text-gray-500">There are no deleted service users to display.</p>
            </div>
            <a href="{{ route('backend.admin.service-users.index') }}"
              class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
              <i class="ph ph-arrow-left"></i>
              Back to Service Users
            </a>
          </div>
        </div>
      </div>
    @endif

    {{-- Restore Confirmation Modal --}}
    <div id="restoreModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="restore-modal-title"
      role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideRestoreModal()"></div>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="ph ph-arrow-counter-clockwise text-green-600 text-2xl"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="restore-modal-title">
                  Restore Service User
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to restore this service user? The profile will be accessible again.
                  </p>
                  <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-900" id="restoreServiceUserName"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="restoreForm" method="POST" action="">
            @csrf
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
              <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <i class="ph ph-arrow-counter-clockwise"></i>
                Restore Service User
              </button>
              <button type="button" onclick="hideRestoreModal()"
                class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <i class="ph ph-x"></i>
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Force Delete Confirmation Modal --}}
    <div id="forceDeleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="force-delete-modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideForceDeleteModal()"></div>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="ph ph-warning text-red-600 text-2xl"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="force-delete-modal-title">
                  Permanently Delete Service User
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    <strong class="text-red-600">Warning:</strong> This action cannot be undone. This will permanently
                    delete the service user and all associated data.
                  </p>
                  <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-sm font-medium text-gray-900" id="forceDeleteServiceUserName"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="forceDeleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
              <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <i class="ph ph-trash"></i>
                Permanently Delete
              </button>
              <button type="button" onclick="hideForceDeleteModal()"
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
      function showRestoreModal(serviceUserId, serviceUserName) {
        const modal = document.getElementById('restoreModal');
        const form = document.getElementById('restoreForm');
        const nameElement = document.getElementById('restoreServiceUserName');

        form.action = '{{ route("backend.admin.service-users.restore", ":id") }}'.replace(':id', serviceUserId);
        nameElement.textContent = serviceUserName;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      function hideRestoreModal() {
        const modal = document.getElementById('restoreModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }

      function showForceDeleteModal(serviceUserId, serviceUserName) {
        const modal = document.getElementById('forceDeleteModal');
        const form = document.getElementById('forceDeleteForm');
        const nameElement = document.getElementById('forceDeleteServiceUserName');

        form.action = '{{ route("backend.admin.service-users.forceDelete", ":id") }}'.replace(':id', serviceUserId);
        nameElement.textContent = serviceUserName;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      function hideForceDeleteModal() {
        const modal = document.getElementById('forceDeleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }

      // Close modals on Escape key
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          hideRestoreModal();
          hideForceDeleteModal();
        }
      });

      document.getElementById('trashSearch').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#trashedServiceUsersTable tbody tr, .trashed-service-user-card');

        rows.forEach(row => {
          const rowText = row.innerText.toLowerCase();
          row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
      });
    </script>
@endsection