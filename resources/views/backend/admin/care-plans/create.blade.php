@extends('layouts.admin')

@section('title', 'Create Care Plan')

@section('content')
  <div class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Create Care Plan</h1>
          <p class="text-sm sm:text-base text-gray-600">Create a new comprehensive care plan for a service user</p>
        </div>
        <a href="{{ route('backend.admin.care-plans.index') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to List</span>
        </a>
      </div>
    </div>

    {{-- Flash Messages --}}
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

    {{-- Create Form --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-file-plus text-blue-600"></i>
          <span>Care Plan Information</span>
        </h2>
      </div>

      <form action="{{ route('backend.admin.care-plans.store') }}" method="POST"
        class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5">
        @csrf

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
              @foreach($serviceUsers as $su)
                <option value="{{ $su->id }}" @selected(old('service_user_id') == $su->id)>
                  {{ $su->full_name ?? ($su->first_name . ' ' . $su->last_name) }}
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
            <input name="title" value="{{ old('title', 'Comprehensive Care Plan') }}"
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
            <input type="date" name="start_date" value="{{ old('start_date') }}"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            @error('start_date')
              <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Next Review
              Date</label>
            <input type="date" name="next_review_date" value="{{ old('next_review_date') }}"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            @error('next_review_date')
              <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Review
              Frequency</label>
            <input name="review_frequency" placeholder="e.g., 24 weeks" value="{{ old('review_frequency') }}"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            @error('review_frequency')
              <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Summary --}}
        <div>
          <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Summary /
            Overview</label>
          <textarea name="summary" rows="4"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            placeholder="Brief overview of the care plan…">{{ old('summary') }}</textarea>
          @error('summary')
            <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Form Actions --}}
        <div
          class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-5 border-t border-gray-200">
          <a href="{{ route('backend.admin.care-plans.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-x"></i>
            <span>Cancel</span>
          </a>
          <button name="action" value="save_draft"
            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-floppy-disk"></i>
            <span>Save Draft</span>
          </button>
          <button name="action" value="publish"
            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium"
            onclick="return confirm('Publish and mark as Active?')">
            <i class="ph ph-check-circle"></i>
            <span>Publish</span>
          </button>
        </div>
      </form>
    </div>

  </div>
@endsection