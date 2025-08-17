@extends('layouts.admin')

@section('title','Edit Staff Profile')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold">Edit Staff Profile</h2>
</div>

@if ($errors->any())
  <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
    <strong>Please fix the following:</strong>
    <ul class="list-disc ml-5">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


<form method="POST" action="{{ 'backend.admin.staff-profiles.update', $staffProfile) }}" class="bg-white p-6 rounded-lg shadow border border-gray-200">
  @csrf
  @method('PUT')

  @include('backend.admin.staff-profiles._form', [
    'users' => $users,
    'staffProfile' => $staffProfile, // enables prefill + 'Update' button text
  ])
</form>
@endsection
