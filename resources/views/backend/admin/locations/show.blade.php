@extends('layouts.admin')

@section('title', 'Location Details')

@section('content')
  @php
    $val = fn($v) => (isset($v) && $v !== '' && $v !== null ? $v : '—');
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
    $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y, h:i A') : '—';

    $badge = $location->status === 'active'
      ? 'bg-green-100 text-green-800 border border-green-200'
      : 'bg-gray-100 text-gray-800 border border-gray-200';

    $typeLabel = ucfirst(str_replace('_', ' ', $location->type));

    // Build full address
    $addressParts = array_filter([
      $location->address_line1,
      $location->address_line2,
      $location->city,
      $location->postcode,
      $location->country
    ]);
    $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : '—';
  @endphp

  <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="flex-1">
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 break-words">
            {{ $location->name }}
          </h1>
          <div
            class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
            <span class="flex items-center gap-1.5">
              <i class="ph ph-buildings flex-shrink-0"></i>
              {{ $typeLabel }}
            </span>
            <span class="hidden sm:inline text-gray-300">•</span>
            @if($location->city)
              <span class="flex items-center gap-1.5">
                <i class="ph ph-map-pin flex-shrink-0"></i>
                {{ $location->city }}{{ $location->postcode ? ', ' . $location->postcode : '' }}
              </span>
              <span class="hidden sm:inline text-gray-300">•</span>
            @endif
            <span class="flex items-center gap-1.5">
              <i class="ph ph-calendar flex-shrink-0"></i>
              <span class="whitespace-nowrap">Created {{ $fmtDate($location->created_at) }}</span>
            </span>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
          <a href="{{ route('backend.admin.locations.edit', $location->id) }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-pencil-simple"></i>
            <span>Edit Location</span>
          </a>
          <a href="{{ route('backend.admin.locations.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-arrow-left"></i>
            <span>Back to List</span>
          </a>
        </div>
      </div>

      {{-- Status Badge --}}
      <div class="inline-flex items-center">
        <span
          class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm {{ $badge }}">
          <span class="w-2 h-2 rounded-full {{ $location->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }}"></span>
          {{ ucfirst($location->status) }}
        </span>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('success') }}</span>
        </div>
      </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">

      {{-- Left Column - Main Info --}}
      <div class="lg:col-span-2 space-y-4 sm:space-y-5 lg:space-y-6">

        {{-- Basic Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-info text-blue-600 flex-shrink-0"></i>
              <span>Basic Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Location
                  Name</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $location->name }}</p>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Type</label>
                <span
                  class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs sm:text-sm font-medium">
                  <i class="ph ph-buildings"></i>
                  {{ $typeLabel }}
                </span>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
                <span
                  class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                  <span
                    class="w-1.5 h-1.5 rounded-full {{ $location->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                  {{ ucfirst($location->status) }}
                </span>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Geofence
                  Radius</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ $location->geofence_radius_m ? $location->geofence_radius_m . ' m' : '—' }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Address Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-map-pin text-purple-600 flex-shrink-0"></i>
              <span>Address Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-3 sm:space-y-4">
              @if($fullAddress !== '—')
                <div>
                  <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Full
                    Address</label>
                  <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $fullAddress }}</p>
                </div>
              @endif
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                @if($location->address_line1)
                  <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Address
                      Line 1</label>
                    <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $location->address_line1 }}</p>
                  </div>
                @endif
                @if($location->address_line2)
                  <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Address
                      Line 2</label>
                    <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $location->address_line2 }}</p>
                  </div>
                @endif
                @if($location->city)
                  <div>
                    <label
                      class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">City</label>
                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $location->city }}</p>
                  </div>
                @endif
                @if($location->postcode)
                  <div>
                    <label
                      class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Postcode</label>
                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $location->postcode }}</p>
                  </div>
                @endif
                @if($location->country)
                  <div>
                    <label
                      class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Country</label>
                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $location->country }}</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Contact Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-phone text-emerald-600 flex-shrink-0"></i>
              <span>Contact Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Phone</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($location->phone) }}</p>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Email</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-all">{{ $val($location->email) }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Location Coordinates Card --}}
        @if($location->lat || $location->lng)
          <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div
              class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
              <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="ph ph-navigation-arrow text-amber-600 flex-shrink-0"></i>
                <span>Location Coordinates</span>
              </h2>
            </div>
            <div class="p-4 sm:p-5 lg:p-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                <div>
                  <label
                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Latitude</label>
                  <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($location->lat) }}</p>
                </div>
                <div>
                  <label
                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Longitude</label>
                  <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($location->lng) }}</p>
                </div>
              </div>
              @if($location->lat && $location->lng)
                <div class="mt-4">
                  <a href="https://www.google.com/maps?q={{ $location->lat }},{{ $location->lng }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors text-sm font-medium">
                    <i class="ph ph-map-trifold"></i>
                    <span>View on Google Maps</span>
                  </a>
                </div>
              @endif
            </div>
          </div>
        @endif

      </div>

      {{-- Right Column - Quick Stats & Actions --}}
      <div class="space-y-4 sm:space-y-5 lg:space-y-6">

        {{-- Quick Stats Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-chart-bar text-emerald-600 flex-shrink-0"></i>
              <span>Quick Stats</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
              <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                <p class="text-xl sm:text-2xl font-bold text-gray-900 break-words">{{ $fmtDate($location->created_at) }}
                </p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Created</p>
              </div>
              @if($location->serviceUsers()->exists())
                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                  <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $location->serviceUsers()->count() }}</p>
                  <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Service Users</p>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Quick Actions Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-lightning text-amber-600 flex-shrink-0"></i>
              <span>Quick Actions</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-2 sm:space-y-3">
              <a href="{{ route('backend.admin.locations.edit', $location->id) }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-50 hover:bg-blue-100 active:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-pencil-simple text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Edit Location</span>
              </a>
              @if($location->email)
                <a href="mailto:{{ $location->email }}"
                  class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-green-50 hover:bg-green-100 active:bg-green-200 text-green-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                  <i class="ph ph-envelope text-base sm:text-lg flex-shrink-0"></i>
                  <span class="font-medium">Send Email</span>
                </a>
              @endif
              <a href="{{ route('backend.admin.locations.index') }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-arrow-left text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Back to List</span>
              </a>
            </div>
          </div>
        </div>

        {{-- System Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-slate-50 to-gray-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-info text-slate-600 flex-shrink-0"></i>
              <span>System Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Location ID:</span>
                <span class="font-medium text-gray-900 break-all">#{{ $location->id }}</span>
              </div>
              @if($location->tenant_id)
                <div class="flex justify-between items-center gap-2">
                  <span class="text-gray-500">Tenant ID:</span>
                  <span class="font-medium text-gray-900 break-all">#{{ $location->tenant_id }}</span>
                </div>
              @endif
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Created:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($location->created_at) }}</span>
              </div>
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Updated:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($location->updated_at) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
@endsection