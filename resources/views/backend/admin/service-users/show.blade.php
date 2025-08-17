@extends('layouts.admin')

@section('title','Service User')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">{{ $serviceUser->full_name }}</h2>
    <a href="{{ route('backend.admin.service-users.index') }}" class="text-blue-600 hover:underline">← Back</a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="mb-2"><strong>DOB:</strong> {{ optional($serviceUser->date_of_birth)->format('d M Y') }} ({{ $serviceUser->age }}y)</p>
    <p class="mb-2"><strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$serviceUser->status ?? ''))) }}</p>
    <p class="mb-2"><strong>Address:</strong> {{ $serviceUser->address_line1 }}, {{ $serviceUser->city }}, {{ $serviceUser->postcode }}</p>
    <p class="mb-2"><strong>GP:</strong> {{ $serviceUser->gp_practice_name ?? '—' }}</p>
  </div>
</div>
@endsection
