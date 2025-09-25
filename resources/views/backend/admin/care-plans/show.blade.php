@extends('layouts.admin')
@section('title','Care Plan')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">{{ $care_plan->title }} (v{{ $care_plan->version }})</h1>
  <div class="space-x-2">
    <a href="{{ route('backend.admin.care-plans.edit',$care_plan) }}" class="px-3 py-2 bg-amber-600 text-white rounded hover:bg-amber-700">Edit</a>
    <a href="{{ route('backend.admin.care-plans.index') }}" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
  </div>
</div>

<div class="bg-white rounded-lg shadow p-4 space-y-3">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div><span class="font-medium">Service User:</span> {{ $care_plan->serviceUser->full_name ?? '' }}</div>
    <div><span class="font-medium">Status:</span> {{ ucfirst($care_plan->status) }}</div>
    <div><span class="font-medium">Start:</span> {{ $care_plan->start_date? $care_plan->start_date->format('d M Y') : '—' }}</div>
    <div><span class="font-medium">Next Review:</span> {{ $care_plan->next_review_date? $care_plan->next_review_date->format('d M Y') : '—' }}</div>
  </div>
  @if($care_plan->summary)
  <div>
    <div class="font-medium mb-1">Summary</div>
    <div class="prose max-w-none">{{ nl2br(e($care_plan->summary)) }}</div>
  </div>
  @endif
  <div class="text-xs text-gray-500">
    Author: {{ $care_plan->author?->name }} on {{ $care_plan->created_at->format('d M Y H:i') }}.
    @if($care_plan->approved_at)
      &nbsp; Approved by: {{ $care_plan->approver?->name }} on {{ $care_plan->approved_at->format('d M Y H:i') }}.
    @endif
  </div>
</div>

{{-- Sections + Goals + Interventions --}}
@include('backend.admin.care-plans.partials._sections', ['plan' => $care_plan])

<div class="flex items-center gap-2 mt-4">
  <a href="{{ route('backend.admin.care-plans.print',$care_plan) }}" target="_blank"
     class="px-3 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Print / PDF</a>
</div>

@include('backend.admin.care-plans.partials._reviews', ['care_plan' => $care_plan])
@include('backend.admin.care-plans.partials._versions', ['care_plan' => $care_plan])
@include('backend.admin.care-plans.partials._signoffs', ['care_plan' => $care_plan])
@endsection
