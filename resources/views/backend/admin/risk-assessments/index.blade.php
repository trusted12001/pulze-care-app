@extends('layouts.admin')

@section('title', 'Risk Assessments')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y') : '—';
  @endphp

  <div class="min-h-screen p-3 sm:p-4 lg:p-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Risk Assessments</h1>
          <p class="text-sm sm:text-base text-gray-600">Manage and view all risk assessments</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
          <a href="{{ route('backend.admin.risk-assessments.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-plus-circle"></i>
            <span>New Assessment</span>
          </a>
          <a href="{{ route('backend.admin.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-arrow-left"></i>
            <span>Back to Dashboard</span>
          </a>
        </div>
      </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8 p-4 sm:p-6">
      <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <input name="search" value="{{ request('search') }}" placeholder="Search title..."
          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
        <select name="service_user_id"
          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
          <option value="">All Service Users</option>
          @foreach($serviceUsers as $su)
            <option value="{{ $su->id }}" @selected(request('service_user_id') == $su->id)>
              {{ method_exists($su, 'getFullNameAttribute') ? $su->full_name : ($su->first_name . ' ' . $su->last_name) }}
            </option>
          @endforeach
        </select>
        <select name="status"
          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
          <option value="">All Status</option>
          <option value="draft" @selected(request('status') === 'draft')>Draft</option>
          <option value="active" @selected(request('status') === 'active')>Active</option>
          <option value="archived" @selected(request('status') === 'archived')>Archived</option>
        </select>
        <button type="submit"
          class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 active:bg-orange-800 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-funnel"></i>
          <span>Filter</span>
        </button>
      </form>
    </div>

    {{-- Risk Assessments List Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i class="ph ph-shield-warning text-orange-600"></i>
            <span>All Risk Assessments</span>
            <span class="text-sm font-normal text-gray-500">({{ $assessments->total() }})</span>
          </h3>
        </div>
      </div>

      {{-- Desktop Table View --}}
      <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service
                User</th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated
              </th>
              <th class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($assessments as $a)
              <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-semibold text-sm sm:text-base">
                      {{ strtoupper(substr(optional($a->serviceUser)->first_name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="text-sm sm:text-base font-medium text-gray-900">
                      {{ optional($a->serviceUser)->full_name ?? optional($a->serviceUser)->first_name . ' ' . optional($a->serviceUser)->last_name }}
                    </div>
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4">
                  <div class="text-sm sm:text-base font-medium text-gray-900">{{ $a->title ?? '—' }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium
                              @if($a->status === 'active') bg-green-100 text-green-800
                              @elseif($a->status === 'draft') bg-yellow-100 text-yellow-800
                              @else bg-gray-100 text-gray-800 @endif">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $a->status === 'active' ? 'bg-green-500' : ($a->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
                    {{ ucfirst($a->status ?? '—') }}
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm sm:text-base text-gray-900">{{ $a->updated_at ? $fmtDate($a->updated_at) : '—' }}
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('backend.admin.risk-assessments.show', $a) }}"
                      class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                      title="Open">
                      <i class="ph ph-eye"></i>
                      <span class="hidden lg:inline">Open</span>
                    </a>
                    <a href="{{ route('backend.admin.risk-assessments.edit', $a) }}"
                      class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                      title="Edit">
                      <i class="ph ph-pencil-simple"></i>
                      <span class="hidden lg:inline">Edit</span>
                    </a>
                    <button type="button"
                      onclick="showDeleteModal({{ $a->id }}, '{{ addslashes($a->title ?? 'Risk Assessment') }}')"
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
                <td colspan="5" class="px-4 sm:px-6 py-12 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <i class="ph ph-shield-warning text-4xl text-gray-300"></i>
                    <p class="text-gray-500 text-sm sm:text-base">No risk assessments yet.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile Card View --}}
      <div class="md:hidden divide-y divide-gray-200">
        @forelse($assessments as $a)
          <div class="p-4 hover:bg-gray-50 transition-colors duration-150 risk-assessment-card">
            <div class="flex items-start gap-4">
              <div
                class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                {{ strtoupper(substr(optional($a->serviceUser)->first_name ?? 'U', 0, 1)) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-2">
                  <div class="min-w-0 flex-1">
                    <h4 class="text-base font-semibold text-gray-900 truncate">{{ $a->title ?? '—' }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">
                      {{ optional($a->serviceUser)->full_name ?? optional($a->serviceUser)->first_name . ' ' . optional($a->serviceUser)->last_name }}
                    </p>
                  </div>
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                              @if($a->status === 'active') bg-green-100 text-green-800
                              @elseif($a->status === 'draft') bg-yellow-100 text-yellow-800
                              @else bg-gray-100 text-gray-800 @endif">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $a->status === 'active' ? 'bg-green-500' : ($a->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
                    {{ ucfirst($a->status ?? '—') }}
                  </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                  @if($a->updated_at)
                    <span class="text-xs text-gray-600">
                      <i class="ph ph-calendar"></i>
                      Updated {{ $fmtDate($a->updated_at) }}
                    </span>
                  @endif
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                  <a href="{{ route('backend.admin.risk-assessments.show', $a) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 active:bg-green-200 transition-colors text-sm font-medium">
                    <i class="ph ph-eye"></i>
                    Open
                  </a>
                  <a href="{{ route('backend.admin.risk-assessments.edit', $a) }}"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors text-sm font-medium">
                    <i class="ph ph-pencil-simple"></i>
                    Edit
                  </a>
                  <button type="button"
                    onclick="showDeleteModal({{ $a->id }}, '{{ addslashes($a->title ?? 'Risk Assessment') }}')"
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
              <i class="ph ph-shield-warning text-4xl text-gray-300"></i>
              <p class="text-gray-500 text-sm sm:text-base">No risk assessments yet.</p>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($assessments->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
          {{ $assessments->links('vendor.pagination.tailwind') }}
        </div>
      @endif
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
                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">Delete Risk Assessment</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Are you sure you want to delete this risk assessment? This action
                    cannot be undone.</p>
                  <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-900" id="deleteRiskAssessmentTitle"></p>
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
                Delete Assessment
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

    {{-- Script --}}
    <script>
      function showDeleteModal(assessmentId, assessmentTitle) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const titleElement = document.getElementById('deleteRiskAssessmentTitle');

        form.action = '{{ route("backend.admin.risk-assessments.destroy", ":id") }}'.replace(':id', assessmentId);
        titleElement.textContent = assessmentTitle;
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
    </script>
@endsection