@extends('layouts.admin')

@section('title', 'Edit Care Plan')

@section('content')
  @php
    $fmtDateTime = fn($d) => $d ? $d->setTimezone('Europe/London')->format('d M Y, h:i A') : '—';

    $statusBadge = match ($care_plan->status) {
      'active' => 'bg-green-100 text-green-800 border border-green-200',
      'draft' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'archived' => 'bg-gray-100 text-gray-800 border border-gray-200',
      default => 'bg-gray-100 text-gray-800 border border-gray-200',
    };
  @endphp

  <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Edit Care Plan</h1>
          <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
            @if(isset($care_plan->version))
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                <i class="ph ph-file-text"></i>
                Version {{ $care_plan->version }}
              </span>
            @endif
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $statusBadge }} font-medium">
              <span
                class="w-1.5 h-1.5 rounded-full {{ $care_plan->status === 'active' ? 'bg-green-500' : ($care_plan->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
              {{ ucfirst($care_plan->status) }}
            </span>
            <span class="flex items-center gap-1.5">
              <i class="ph ph-clock"></i>
              Last updated: {{ $fmtDateTime($care_plan->updated_at) }}
            </span>
          </div>
        </div>
        <a href="{{ route('backend.admin.care-plans.show', $care_plan) }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to Plan</span>
        </a>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('success') }}</span>
        </div>
      </div>
    @endif
    @if(session('warning'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-warning-circle text-amber-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('warning') }}</span>
        </div>
      </div>
    @endif
    @if($errors->any())
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
        <div class="flex items-start gap-2 text-sm sm:text-base">
          <i class="ph ph-warning-circle text-red-600 mt-0.5 flex-shrink-0"></i>
          <div class="min-w-0 flex-1">
            <strong class="font-semibold block mb-1">Please fix the following:</strong>
            <ul class="list-disc ml-4 sm:ml-5 space-y-1">
              @foreach($errors->all() as $e)
                <li class="break-words">{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
      {{-- Main Form --}}
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-pencil-simple text-blue-600 flex-shrink-0"></i>
              <span>Care Plan Details</span>
            </h2>
          </div>

          <form method="POST" action="{{ route('backend.admin.care-plans.update', $care_plan) }}"
            class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" id="cp-action" value="save">

            {{-- Service User & Title --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">
                  Service User <span class="text-red-500">*</span>
                </label>
                <select name="service_user_id"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  required>
                  <option value="">Select...</option>
                  @foreach($serviceUsers ?? [] as $su)
                    <option value="{{ $su->id }}" @selected(old('service_user_id', $care_plan->service_user_id) == $su->id)>
                      {{ method_exists($su, 'getFullNameAttribute') ? $su->full_name : ($su->first_name . ' ' . $su->last_name) }}
                    </option>
                  @endforeach
                </select>
                @error('service_user_id')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">
                  Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $care_plan->title) }}"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  required>
                @error('title')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Start
                  Date</label>
                <input type="date" name="start_date"
                  value="{{ old('start_date', optional($care_plan->start_date)->format('Y-m-d')) }}"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @error('start_date')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Next Review
                  Date</label>
                <input type="date" name="next_review_date"
                  value="{{ old('next_review_date', optional($care_plan->next_review_date)->format('Y-m-d')) }}"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @error('next_review_date')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Review
                  Frequency</label>
                <input type="text" name="review_frequency" placeholder="e.g., 6 Weeks"
                  value="{{ old('review_frequency', $care_plan->review_frequency) }}"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @error('review_frequency')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- Status & Version --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
              <div>
                <label
                  class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
                <select name="status"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  @php $statuses = ['draft' => 'Draft', 'active' => 'Active', 'archived' => 'Archived']; @endphp
                  @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $care_plan->status) === $val)>{{ $label }}</option>
                  @endforeach
                </select>
                @error('status')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              @if(isset($care_plan->version))
                <div>
                  <label
                    class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Version</label>
                  <input type="number" name="version" min="1" value="{{ old('version', $care_plan->version) }}"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  @error('version')
                    <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
              @endif
            </div>

            {{-- Summary --}}
            <div>
              <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Summary /
                Overview</label>
              <textarea name="summary" rows="6"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                placeholder="Brief overview of the care plan…">{{ old('summary', $care_plan->summary) }}</textarea>
              @error('summary')
                <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Form Actions --}}
            <div
              class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-5 border-t border-gray-200">
              <a href="{{ route('backend.admin.care-plans.show', $care_plan) }}"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-x"></i>
                <span>Cancel</span>
              </a>
              <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-floppy-disk"></i>
                <span>Save Changes</span>
              </button>
              <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium"
                onclick="document.getElementById('cp-action').value='publish'; return confirm('Publish and mark as Active?')">
                <i class="ph ph-check-circle"></i>
                <span>Save & Publish</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Sidebar --}}
      <div class="space-y-4 sm:space-y-5 lg:space-y-6">
        {{-- Metadata Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-slate-50 to-gray-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-info text-slate-600 flex-shrink-0"></i>
              <span>Metadata</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <dl class="space-y-3 text-sm">
              <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Author</dt>
                <dd class="text-gray-900 font-medium">{{ optional($care_plan->author ?? null)->name ?? '—' }}</dd>
              </div>
              <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created</dt>
                <dd class="text-gray-900 font-medium">{{ $fmtDateTime($care_plan->created_at) }}</dd>
              </div>
              <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Updated</dt>
                <dd class="text-gray-900 font-medium">{{ $fmtDateTime($care_plan->updated_at) }}</dd>
              </div>
            </dl>
          </div>
        </div>

        {{-- Tips Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-lightbulb text-amber-600 flex-shrink-0"></i>
              <span>Tips</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <ul class="space-y-2 text-sm text-gray-700">
              <li class="flex items-start gap-2">
                <i class="ph ph-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                <span>Set a realistic review frequency (e.g., 6–12 weeks).</span>
              </li>
              <li class="flex items-start gap-2">
                <i class="ph ph-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                <span>Keep Summary concise; detailed instructions live in Sections/Goals.</span>
              </li>
              <li class="flex items-start gap-2">
                <i class="ph ph-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                <span>Use "Save & Publish" to activate for staff immediately.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection