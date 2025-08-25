@extends('layouts.admin')

@section('title', 'Location Details')

@section('content')
<div class="max-w-3xl mx-auto py-10">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Location Details</h2>

  </div>

  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Name</h4>
        <p class="text-lg font-semibold text-gray-800">{{ $location->name }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Type</h4>
        <p class="text-gray-700">{{ ucfirst(str_replace('_',' ',$location->type)) }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Status</h4>
        @php $badge = $location->status === 'active' ? 'bg-green-500' : 'bg-gray-500'; @endphp
        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $badge }}">
          {{ ucfirst($location->status) }}
        </span>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Geofence Radius</h4>
        <p class="text-gray-700">{{ $location->geofence_radius_m ? $location->geofence_radius_m.' m' : '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Address</h4>
        <p class="text-gray-700">
          {{ $location->address_line1 }}<br>
          {{ $location->address_line2 }}<br>
          {{ $location->city }} {{ $location->postcode }}<br>
          {{ $location->country }}
        </p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Contact</h4>
        <p class="text-gray-700">
          {{ $location->phone ?: '—' }}<br>
          {{ $location->email ?: '—' }}
        </p>
      </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Latitude</h4>
        <p class="text-gray-700">{{ $location->lat ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Longitude</h4>
        <p class="text-gray-700">{{ $location->lng ?? '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Created</h4>
        <p class="text-gray-700">{{ optional($location->created_at)->format('d M Y H:i') }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Last Updated</h4>
        <p class="text-gray-700">{{ optional($location->updated_at)->format('d M Y H:i') }}</p>
      </div>
    </div>
  </div>

  <div class="mt-6 flex items-center justify-between">
    <a href="{{ route('backend.admin.locations.index') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
  </div>

</div>
@endsection
