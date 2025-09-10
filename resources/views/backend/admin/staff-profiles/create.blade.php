@extends('layouts.admin')

@section('title','Staff Profile')

@section('content')
<div class="flex items-center justify-between mb-7">
  <h2 class="text-3xl font-bold text-black">Create Staff Profile</h2>
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

<form method="POST" action="{{ route('backend.admin.staff-profiles.store') }}" class="bg-white p-6 rounded-lg shadow border border-gray-200">
  @csrf
  @include('backend.admin.staff-profiles._form', [
    'users' => $users,
    'locations' => $locations ?? collect(),
    'managers' => $managers ?? collect(),
  ])
</form>
@endsection
