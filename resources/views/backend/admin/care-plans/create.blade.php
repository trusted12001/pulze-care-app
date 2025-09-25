@extends('layouts.admin')
@section('title','New Care Plan')
@section('content')
<h1 class="text-2xl font-semibold mb-6">New Care Plan</h1>
@include('backend.admin.care-plans.partials._form', [
  'route' => route('backend.admin.care-plans.store'),
  'method' => 'POST',
  'plan' => null,
  'serviceUsers' => $serviceUsers,
])
@endsection
