@extends('layouts.superadmin')
@section('title','Edit Risk Assessment')
@section('content')
  <h1 class="text-2xl font-semibold mb-6">Edit: {{ $assessment->title }}</h1>
  @include('backend.admin.risk-assessments.partials._form', [
      'route' => route('backend.admin.risk-assessments.update', $assessment),
      'method' => 'PUT',
      'assessment' => $assessment,
  ])
@endsection
