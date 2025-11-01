@extends('layouts.admin')
@section('title','Add Risk Type')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Add Risk Type</h1>
  <a href="{{ route('backend.admin.risk-assessments.index') }}"
     class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
</div>

@if($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc list-inside">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('backend.admin.risk-types.store') }}"
      class="bg-white rounded-lg shadow p-4 space-y-4">
  @csrf

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Name</label>
      <input name="name" value="{{ old('name') }}" required
             class="border rounded w-full px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm font-medium mb-1">Description (optional)</label>
      <textarea name="description" rows="3"
                class="border rounded w-full px-3 py-2">{{ old('description') }}</textarea>
    </div>
  </div>

  <div class="flex items-center gap-3">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
    <a href="{{ route('backend.admin.risk-assessments.index') }}"
       class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</a>
  </div>
</form>
@endsection
