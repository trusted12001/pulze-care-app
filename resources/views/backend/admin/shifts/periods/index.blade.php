@extends('layouts.admin')

@section('title', 'Rota Periods')

@section('content')

  <div class="min-h-screen p-3 sm:p-4 lg:p-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Rota Periods</h1>
          <p class="text-sm sm:text-base text-gray-600">Manage rota periods and schedules</p>
        </div>
        <a href="{{ route('backend.admin.index') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to Dashboard</span>
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

    {{-- Create Period Form --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8 overflow-hidden">
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-plus-circle text-teal-600"></i>
          <span>Create New Period</span>
        </h2>
      </div>
      <form method="POST" action="{{ route('backend.admin.rota-periods.store') }}" class="p-4 sm:p-6">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5">
          <div class="sm:col-span-2">
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Location <span
                class="text-red-500">*</span></label>
            <select name="location_id"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
              required>
              <option value="">Select Location…</option>
              @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Start Date <span
                class="text-red-500">*</span></label>
            <input type="date" name="start_date"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
              required />
          </div>
          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">End Date <span
                class="text-red-500">*</span></label>
            <input type="date" name="end_date"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
              required />
          </div>
          <div class="flex items-end">
            <button type="submit"
              class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 active:bg-teal-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
              <i class="ph ph-plus-circle"></i>
              <span>Create</span>
            </button>
          </div>
        </div>
      </form>
    </div>

    {{-- Periods List Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-calendar text-teal-600"></i>
          <span>All Periods</span>
          <span class="text-sm font-normal text-gray-500">({{ $periods->total() }})</span>
        </h3>
      </div>

      {{-- Desktop Table View --}}
      <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Location</th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dates
              </th>
              <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
              </th>
              <th class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($periods as $p)
              <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center text-white">
                      <i class="ph ph-map-pin text-sm sm:text-base"></i>
                    </div>
                    <div class="text-sm sm:text-base font-medium text-gray-900">{{ $p->location->name }}</div>
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm sm:text-base text-gray-900">{{ $p->start_date->format('d M') }} –
                    {{ $p->end_date->format('d M Y') }}</div>
                  <div class="text-xs text-gray-500">{{ $p->start_date->diffInDays($p->end_date) + 1 }} days</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  @php
                    $badge = match ($p->status) {
                      'published' => 'bg-green-100 text-green-800',
                      'locked' => 'bg-gray-100 text-gray-800',
                      default => 'bg-yellow-100 text-yellow-800',
                    };
                  @endphp
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $p->status === 'published' ? 'bg-green-500' : ($p->status === 'locked' ? 'bg-gray-500' : 'bg-yellow-500') }}"></span>
                    {{ ucfirst($p->status) }}
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="{{ route('backend.admin.rota-periods.show', $p) }}"
                    class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors duration-200"
                    title="Open">
                    <i class="ph ph-eye"></i>
                    <span class="hidden lg:inline">Open</span>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 sm:px-6 py-12 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <i class="ph ph-calendar text-4xl text-gray-300"></i>
                    <p class="text-gray-500 text-sm sm:text-base">No periods yet.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile Card View --}}
      <div class="md:hidden divide-y divide-gray-200">
        @forelse($periods as $p)
          @php
            $badge = match ($p->status) {
              'published' => 'bg-green-100 text-green-800',
              'locked' => 'bg-gray-100 text-gray-800',
              default => 'bg-yellow-100 text-yellow-800',
            };
          @endphp
          <div class="p-4 hover:bg-gray-50 transition-colors duration-150 rota-period-card">
            <div class="flex items-start gap-4">
              <div
                class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center text-white flex-shrink-0">
                <i class="ph ph-calendar text-xl"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-2">
                  <div class="min-w-0 flex-1">
                    <h4 class="text-base font-semibold text-gray-900 truncate">{{ $p->location->name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $p->start_date->format('d M') }} –
                      {{ $p->end_date->format('d M Y') }}</p>
                  </div>
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">
                    <span
                      class="w-1.5 h-1.5 rounded-full {{ $p->status === 'published' ? 'bg-green-500' : ($p->status === 'locked' ? 'bg-gray-500' : 'bg-yellow-500') }}"></span>
                    {{ ucfirst($p->status) }}
                  </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                  <span class="text-xs text-gray-600">
                    <i class="ph ph-calendar-check"></i>
                    {{ $p->start_date->diffInDays($p->end_date) + 1 }} days
                  </span>
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                  <a href="{{ route('backend.admin.rota-periods.show', $p) }}"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 active:bg-teal-200 transition-colors text-sm font-medium">
                    <i class="ph ph-eye"></i>
                    Open Period
                  </a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="p-12 text-center">
            <div class="flex flex-col items-center gap-3">
              <i class="ph ph-calendar text-4xl text-gray-300"></i>
              <p class="text-gray-500 text-sm sm:text-base">No periods yet.</p>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($periods->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
          {{ $periods->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>

  </div>

@endsection