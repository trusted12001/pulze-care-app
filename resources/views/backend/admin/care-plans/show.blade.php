@extends('layouts.admin')

@section('title', 'Care Plan')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? $d->format('d M Y') : '—';
    $fmtDateTime = fn($d) => $d ? $d->format('d M Y, h:i A') : '—';

    $statusBadge = match ($care_plan->status) {
      'active' => 'bg-green-100 text-green-800 border border-green-200',
      'draft' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'archived' => 'bg-gray-100 text-gray-800 border border-gray-200',
      default => 'bg-gray-100 text-gray-800 border border-gray-200',
    };
  @endphp

  <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="flex-1">
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4 mb-3">
            <div class="flex-1">
              <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 break-words">
                {{ $care_plan->title }}
              </h1>
              <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                  <i class="ph ph-file-text"></i>
                  Version {{ $care_plan->version }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $statusBadge }} font-medium">
                  <span
                    class="w-1.5 h-1.5 rounded-full {{ $care_plan->status === 'active' ? 'bg-green-500' : ($care_plan->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
                  {{ ucfirst($care_plan->status) }}
                </span>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
              <a href="{{ route('backend.admin.care-plans.edit', $care_plan) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-pencil-simple"></i>
                <span>Edit Plan</span>
              </a>
              <a href="{{ route('backend.admin.care-plans.print', $care_plan) }}" target="_blank"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-printer"></i>
                <span>Print / PDF</span>
              </a>
              <a href="{{ route('backend.admin.care-plans.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-arrow-left"></i>
                <span>Back to List</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('success') }}</span>
        </div>
      </div>
    @endif

    {{-- Overview Card --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
      <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-info text-blue-600 flex-shrink-0"></i>
          <span>Plan Overview</span>
        </h2>
      </div>
      <div class="p-4 sm:p-5 lg:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-4 sm:mb-5">
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Service
              User</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold break-words">
              {{ $care_plan->serviceUser->full_name ?? '—' }}
            </p>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
            <span
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs sm:text-sm font-medium {{ $statusBadge }}">
              <span
                class="w-1.5 h-1.5 rounded-full {{ $care_plan->status === 'active' ? 'bg-green-500' : ($care_plan->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
              {{ ucfirst($care_plan->status) }}
            </span>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Start
              Date</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold">{{ $fmtDate($care_plan->start_date) }}</p>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Next
              Review</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold">{{ $fmtDate($care_plan->next_review_date) }}</p>
          </div>
        </div>

        @if($care_plan->summary)
          <div class="mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-gray-200">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Summary</label>
            <div class="prose prose-sm sm:prose-base max-w-none text-gray-700 whitespace-pre-line">
              {!! nl2br(e($care_plan->summary)) !!}
            </div>
          </div>
        @endif

        <div class="mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
              <i class="ph ph-user"></i>
              <span>Author: <strong class="text-gray-700">{{ $care_plan->author?->name ?? '—' }}</strong> on
                {{ $fmtDateTime($care_plan->created_at) }}</span>
            </span>
            @if($care_plan->approved_at)
              <span class="flex items-center gap-1.5">
                <i class="ph ph-check-circle"></i>
                <span>Approved by: <strong class="text-gray-700">{{ $care_plan->approver?->name ?? '—' }}</strong> on
                  {{ $fmtDateTime($care_plan->approved_at) }}</span>
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Sections + Goals + Interventions --}}
    @include('backend.admin.care-plans.partials._sections', ['plan' => $care_plan])

    {{-- Reviews Section --}}
    @include('backend.admin.care-plans.partials._reviews', ['care_plan' => $care_plan])

    {{-- Version History --}}
    @include('backend.admin.care-plans.partials._versions', ['care_plan' => $care_plan])

    {{-- Sign-offs --}}
    @include('backend.admin.care-plans.partials._signoffs', ['care_plan' => $care_plan])

  </div>
@endsection