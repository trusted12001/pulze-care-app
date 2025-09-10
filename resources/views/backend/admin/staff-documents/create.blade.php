@extends('layouts.admin')

@section('title','Upload Document')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-3xl font-bold text-black">Upload Document — {{ $staffProfile->user->full_name ?? '—' }}</h2>
  <a href="{{ route('backend.admin.staff-profiles.documents.index', $staffProfile) }}" class="text-blue-600 hover:underline">← Back</a>
</div>

@include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

@if ($errors->any())
  <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
    <strong>Please fix the following:</strong>
    <ul class="list-disc ml-5">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
  </div>
@endif

<form method="POST" enctype="multipart/form-data"
      action="{{ route('backend.admin.staff-profiles.documents.store', $staffProfile) }}"
      class="bg-white p-6 rounded-lg shadow border border-gray-200">
  @csrf
  @include('backend.admin.staff-documents._form', ['categories' => $categories])
</form>
@endsection
