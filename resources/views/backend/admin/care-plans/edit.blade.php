@extends('layouts.admin')
@section('title', 'Edit Care Plan')

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
  <h1 class="text-2xl font-semibold">Edit Care Plan</h1>
  <div class="text-sm text-gray-500">
    @if(isset($care_plan->version))
      <span class="mr-3">Version: <span class="font-medium">{{ $care_plan->version }}</span></span>
    @endif
    <span class="mr-3">Status: <span class="px-2 py-0.5 rounded bg-gray-100">{{ ucfirst($care_plan->status) }}</span></span>
    <span>Last updated: {{ optional($care_plan->updated_at)->setTimezone('Europe/London')->format('d M Y H:i') }}</span>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  {{-- Main form --}}
  <div class="lg:col-span-2">
    <div class="bg-white rounded shadow p-4">
      <form method="POST" action="{{ route('backend.admin.care-plans.update', $care_plan) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="cp-action" value="save">

        {{-- Identity / Linking --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Service User</label>
            <select name="service_user_id" class="border rounded px-3 py-2 w-full" required>
              <option value="">Select...</option>
              @foreach($serviceUsers ?? [] as $su)
                <option value="{{ $su->id }}" @selected(old('service_user_id', $care_plan->service_user_id) == $su->id)>
                  {{ method_exists($su,'getFullNameAttribute') ? $su->full_name : ($su->first_name.' '.$su->last_name) }}
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $care_plan->title) }}" class="border rounded px-3 py-2 w-full" required>
          </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($care_plan->start_date)->format('Y-m-d')) }}" class="border rounded px-3 py-2 w-full">
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Next review date</label>
            <input type="date" name="next_review_date" value="{{ old('next_review_date', optional($care_plan->next_review_date)->format('Y-m-d')) }}" class="border rounded px-3 py-2 w-full">
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Review frequency</label>
            <input type="text" name="review_frequency" placeholder="e.g., 6 Weeks" value="{{ old('review_frequency', $care_plan->review_frequency) }}" class="border rounded px-3 py-2 w-full">
          </div>
        </div>

        {{-- Status (keep minimal; controller can validate allowed values) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="border rounded px-3 py-2 w-full">
              @php $statuses = ['draft'=>'Draft','active'=>'Active','archived'=>'Archived']; @endphp
              @foreach($statuses as $val=>$label)
                <option value="{{ $val }}" @selected(old('status', $care_plan->status) === $val)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          @if(isset($care_plan->version))
          <div>
            <label class="block text-sm text-gray-600 mb-1">Version</label>
            <input type="number" name="version" min="1" value="{{ old('version', $care_plan->version) }}" class="border rounded px-3 py-2 w-full">
          </div>
          @endif
        </div>

        {{-- Summary --}}
        <div>
          <label class="block text-sm text-gray-600 mb-1">Summary / Overview</label>
          <textarea name="summary" rows="6" class="border rounded px-3 py-2 w-full" placeholder="Brief overview of the care plan…">{{ old('summary', $care_plan->summary) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 pt-2">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

          {{-- Optional “Publish” shortcut: will set status to active in controller if you handle it --}}
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded"
                  onclick="document.getElementById('cp-action').value='publish'">
            Save & Publish
          </button>

          <a href="{{ route('backend.admin.care-plans.show', $care_plan) }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Sidebar / Meta --}}
  <div>
    <div class="bg-white rounded shadow p-4 mb-4">
      <div class="text-sm text-gray-600 mb-2">Metadata</div>
      <dl class="text-sm">
        <dt class="text-gray-500">Author</dt>
        <dd class="mb-2">{{ optional($care_plan->author ?? null)->name ?? '—' }}</dd>

        <dt class="text-gray-500">Created</dt>
        <dd class="mb-2">{{ optional($care_plan->created_at)->setTimezone('Europe/London')->format('d M Y H:i') }}</dd>

        <dt class="text-gray-500">Updated</dt>
        <dd class="mb-2">{{ optional($care_plan->updated_at)->setTimezone('Europe/London')->format('d M Y H:i') }}</dd>
      </dl>
    </div>

    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-600 mb-2">Tips</div>
      <ul class="list-disc pl-5 text-sm text-gray-600">
        <li>Set a realistic review frequency (e.g., 6–12 weeks).</li>
        <li>Keep Summary concise; detailed instructions live in Sections/Goals.</li>
        <li>Use “Save & Publish” to activate for staff immediately.</li>
      </ul>
    </div>
  </div>
</div>
@endsection
