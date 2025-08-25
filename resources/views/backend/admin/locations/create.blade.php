@extends('layouts.admin')

@section('title','Add Location')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Add Location</h2>
    <a href="{{ route('backend.admin.locations.index') }}" class="text-blue-600 hover:underline">← Back</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md shadow-sm">
      <strong>Please fix the following:</strong>
      <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('backend.admin.locations.store') }}"
        class="bg-white p-6 rounded-lg shadow border border-gray-200">
    @include('backend.admin.locations._form')
  </form>
</div>
@endsection
