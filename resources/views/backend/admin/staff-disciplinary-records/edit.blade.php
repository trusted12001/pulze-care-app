@extends('layouts.admin')

@section('title','Edit Disciplinary')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-3xl font-bold text-black">Edit Disciplinary — {{ $staffProfile->user->full_name ?? '—' }}</h2>
  <a href="{{ route('backend.admin.staff-profiles.disciplinary.index', $staffProfile) }}" class="text-blue-600 hover:underline">← Back</a>
</div>

@include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

@if ($errors->any())
  <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
    <strong>Please fix the following:</strong>
    <ul class="list-disc ml-5">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
  </div>
@endif

<form method="POST" action="{{ route('backend.admin.staff-profiles.disciplinary.update', [$staffProfile, $record]) }}"
      class="bg-white p-6 rounded-lg shadow border border-gray-200">
  @csrf @method('PUT')
  @include('backend.admin.staff-disciplinary-records._form', ['record' => $record])
</form>
@endsection
