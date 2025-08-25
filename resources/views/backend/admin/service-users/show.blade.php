@extends('layouts.admin')

@section('title', 'Service User Details')

@section('content')
<div class="max-w-4xl mx-auto py-10">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Service User Details</h2>
    <a href="{{ route('backend.admin.service-users.index') }}" class="text-blue-600 hover:underline">← Back to List</a>
  </div>

  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Name</h4>
        <p class="text-lg font-semibold text-gray-800">{{ $su->full_name }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Date of Birth</h4>
        <p class="text-gray-700">{{ optional($su->date_of_birth)->format('d M Y') ?: '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Status</h4>
        @php $badge = $su->status === 'active' ? 'bg-green-500' : ($su->status === 'discharged' ? 'bg-gray-500' : 'bg-amber-500'); @endphp
        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $badge }}">
          {{ ucfirst($su->status) }}
        </span>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Location</h4>
        <p class="text-gray-700">{{ $su->location->name ?: '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Admission Date</h4>
        <p class="text-gray-700">{{ optional($su->admission_date)->format('d M Y') ?: '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Expected Discharge</h4>
        <p class="text-gray-700">{{ optional($su->expected_discharge_date)->format('d M Y') ?: '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Discharge Date</h4>
        <p class="text-gray-700">{{ optional($su->discharge_date)->format('d M Y') ?: '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Primary Phone</h4>
        <p class="text-gray-700">{{ $su->primary_phone ?: '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Address</h4>
        <p class="text-gray-700">
          {{ $su->address_line1 }}<br>
          {{ $su->address_line2 }}<br>
          {{ $su->city }} {{ $su->postcode }}<br>
          {{ $su->country }}
        </p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">NHS Number</h4>
        <p class="text-gray-700">{{ $su->nhs_number ?: '—' }}</p>
      </div>
    </div>

  </div>

  <div class="mt-6 flex items-center justify-between">
    <a href="{{ route('backend.admin.service-users.index') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
    <div class="space-x-2">
      <a href="{{ route('backend.admin.service-users.edit', $su) }}"
         class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition text-sm">Edit</a>
      <form action="{{ route('backend.admin.service-users.destroy', $su) }}" method="POST" class="inline-block"
            onsubmit="return confirm('Move this service user to recycle bin?');">
        @csrf @method('DELETE')
        <button class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 transition text-sm">
          Delete
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
