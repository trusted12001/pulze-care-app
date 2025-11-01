@extends('layouts.admin')
@section('title', 'Edit Risk Assessment')

@section('content')
{{-- Flash / Errors --}}
@if(session('success'))
  <div class="mb-3 rounded bg-green-50 text-green-800 px-3 py-2 border border-green-200">{{ session('success') }}</div>
@endif
@if(session('warning'))
  <div class="mb-3 rounded bg-amber-50 text-amber-800 px-3 py-2 border border-amber-200">{{ session('warning') }}</div>
@endif
@if($errors->any())
  <div class="mb-3 rounded bg-red-50 text-red-800 px-3 py-2 border border-red-200">
    <ul class="list-disc pl-5">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Edit Risk Assessment</h1>

  <div class="text-sm text-gray-500">
    <span class="mr-3">
      Status:
      <span class="px-2 py-0.5 rounded bg-gray-100">
        {{ ucfirst($riskAssessment->status ?? 'draft') }}
      </span>
    </span>
    <span>
      Last updated:
      {{ optional($riskAssessment->updated_at)->setTimezone('Europe/London')->format('d M Y H:i') }}
    </span>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  {{-- Main form --}}
  <div class="lg:col-span-2">
    <div class="bg-white rounded shadow p-4">
      <form method="POST" action="{{ route('backend.admin.risk-assessments.update', $riskAssessment) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="ra-action" value="save">

        {{-- Identity / Linking --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Service User</label>
            <select name="service_user_id" class="border rounded px-3 py-2 w-full" required>
              <option value="">Select...</option>
              @foreach($serviceUsers as $su)
                @php
                  $full = method_exists($su,'getFullNameAttribute')
                          ? $su->full_name
                          : trim(($su->first_name ?? '').' '.($su->last_name ?? ''));
                @endphp
                <option value="{{ $su->id }}" @selected(old('service_user_id', $riskAssessment->service_user_id) == $su->id)>
                  {{ $full }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $riskAssessment->title) }}"
                   class="border rounded px-3 py-2 w-full"
                   required>
          </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Start date</label>
            <input type="date"
                   name="start_date"
                   value="{{ old('start_date', optional($riskAssessment->start_date)->format('Y-m-d')) }}"
                   class="border rounded px-3 py-2 w-full">
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Next review date</label>
            <input type="date"
                   name="next_review_date"
                   value="{{ old('next_review_date', optional($riskAssessment->next_review_date)->format('Y-m-d')) }}"
                   class="border rounded px-3 py-2 w-full">
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Review frequency</label>
            <input type="text"
                   name="review_frequency"
                   placeholder="e.g., 12 Weeks"
                   value="{{ old('review_frequency', $riskAssessment->review_frequency) }}"
                   class="border rounded px-3 py-2 w-full">
          </div>
        </div>

        {{-- Status --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            @php $status = old('status', $riskAssessment->status ?? 'draft'); @endphp
            <select name="status" class="border rounded px-3 py-2 w-full">
              <option value="draft"    @selected($status === 'draft')>Draft</option>
              <option value="active"   @selected($status === 'active')>Active</option>
              <option value="archived" @selected($status === 'archived')>Archived</option>
            </select>
          </div>
        </div>

        {{-- Summary --}}
        <div>
          <label class="block text-sm text-gray-600 mb-1">Summary / Overview</label>
          <textarea name="summary"
                    rows="6"
                    class="border rounded px-3 py-2 w-full"
                    placeholder="Brief overview of the risk assessment…">{{ old('summary', $riskAssessment->summary) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 pt-2">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

          {{-- Optional “Publish” shortcut: set to active in controller if you handle it --}}
          <button type="submit"
                  class="px-4 py-2 bg-green-600 text-white rounded"
                  onclick="document.getElementById('ra-action').value='publish'">
            Save & Publish
          </button>

          <a href="{{ route('backend.admin.risk-assessments.show', $riskAssessment) }}"
             class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Sidebar / Meta --}}
  <div>
    <div class="bg-white rounded shadow p-4 mb-4">
      <div class="text-sm text-gray-600 mb-2">Metadata</div>
      <dl class="text-sm">
        <dt class="text-gray-500">Service User</dt>
        <dd class="mb-2">
          {{ optional($riskAssessment->serviceUser)->full_name
              ?? trim((optional($riskAssessment->serviceUser)->first_name ?? '').' '.(optional($riskAssessment->serviceUser)->last_name ?? '')) ?: '—' }}
        </dd>

        <dt class="text-gray-500">Created</dt>
        <dd class="mb-2">{{ optional($riskAssessment->created_at)->setTimezone('Europe/London')->format('d M Y H:i') }}</dd>

        <dt class="text-gray-500">Updated</dt>
        <dd class="mb-2">{{ optional($riskAssessment->updated_at)->setTimezone('Europe/London')->format('d M Y H:i') }}</dd>
      </dl>
    </div>

    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-600 mb-2">Tips</div>
      <ul class="list-disc pl-5 text-sm text-gray-600">
        <li>Use a concise title; details live in items/controls.</li>
        <li>Set realistic review frequency (e.g., 8–12 weeks).</li>
        <li>“Save & Publish” will activate the profile for staff (if handled in controller).</li>
      </ul>
    </div>
  </div>
</div>
@endsection
