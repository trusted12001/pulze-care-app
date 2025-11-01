@extends('layouts.admin')
@section('title','New Risk Assessment')

@section('content')
<h1 class="text-2xl font-semibold mb-6">New Risk Assessment</h1>

@include('backend.admin.risk-assessments.partials._form', [
  'route' => route('backend.admin.risk-assessments.store'),
  'method' => 'POST',
  'assessment' => null,
  'serviceUsers' => $serviceUsers,
])
@endsection
