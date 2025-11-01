@extends('layouts.admin')
@section('title','Add Risk Item')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">
    Add Risk Item – {{ $profile->title }}
  </h1>
  <a href="{{ route('backend.admin.risk-assessments.show', $profile) }}"
     class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
</div>

@if($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc list-inside">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('backend.admin.risk-items.store', $profile) }}"
      class="bg-white rounded-lg shadow p-4 space-y-4">
  @csrf

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if($riskTypes->count())
      <div>
        <label class="block text-sm font-medium mb-1">Risk Type</label>
        <select name="risk_type_id" class="border rounded w-full px-3 py-2">
          <option value="">Select...</option>
          @foreach($riskTypes as $rt)
            <option value="{{ $rt->id }}" @selected($selectedTypeId == $rt->id)>{{ $rt->name }}</option>
          @endforeach
        </select>
      </div>
    @endif

    <div>
      <label class="block text-sm font-medium mb-1">Title (optional)</label>
      <input name="title" value="{{ old('title') }}" class="border rounded w-full px-3 py-2">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Likelihood (1–5)</label>
      <input type="number" name="likelihood" min="1" max="5" value="{{ old('likelihood',3) }}" required class="border rounded w-full px-3 py-2">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Severity (1–5)</label>
      <input type="number" name="severity" min="1" max="5" value="{{ old('severity',3) }}" required class="border rounded w-full px-3 py-2">
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Context</label>
    <textarea name="context" rows="4" required class="border rounded w-full px-3 py-2">{{ old('context') }}</textarea>
  </div>

  <div class="flex items-center gap-3">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Item</button>
    <a href="{{ route('backend.admin.risk-assessments.show', $profile) }}"
       class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</a>
  </div>
</form>
@endsection
