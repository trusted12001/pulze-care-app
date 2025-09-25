@extends('layouts.admin')
@section('title','Risk Assessment')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">{{ $risk_assessment->title }}</h1>
  <div class="space-x-2">
    <a href="{{ route('backend.admin.risk-assessments.edit',$risk_assessment) }}"
       class="px-3 py-2 bg-amber-600 text-white rounded hover:bg-amber-700">Edit</a>
    <a href="{{ route('backend.admin.risk-assessments.index') }}"
       class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
  </div>
</div>

<div class="bg-white rounded-lg shadow p-4 space-y-3">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div><span class="font-medium">Service User:</span> {{ $risk_assessment->serviceUser->full_name ?? '' }}</div>
    <div><span class="font-medium">Risk Type:</span> {{ $risk_assessment->riskType->name }}</div>
    <div><span class="font-medium">Status:</span> {{ ucfirst($risk_assessment->status) }}</div>
    <div><span class="font-medium">Score:</span> {{ $risk_assessment->risk_score }}</div>
    <div><span class="font-medium">Band:</span>
      @include('backend.admin.risk-assessments.partials.band-badge',['band'=>$risk_assessment->risk_band])
    </div>
    <div><span class="font-medium">Next Review:</span>
      {{ $risk_assessment->next_review_date? \Illuminate\Support\Carbon::parse($risk_assessment->next_review_date)->format('d M Y') : '—' }}
    </div>
  </div>
  <div>
    <div class="font-medium mb-1">Context / Notes</div>
    <div class="prose max-w-none">{{ nl2br(e($risk_assessment->context)) ?: '—' }}</div>
  </div>
  <div class="text-xs text-gray-500">
    Created by: {{ $risk_assessment->creator?->name }} on {{ $risk_assessment->created_at->format('d M Y H:i') }}.
    @if($risk_assessment->approved_at)
      Approved by: {{ $risk_assessment->approver?->name }} on {{ $risk_assessment->approved_at->format('d M Y H:i') }}.
    @endif
  </div>
</div>
@endsection
