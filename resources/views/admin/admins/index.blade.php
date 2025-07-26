@extends('layouts.admin')

@section('title', 'Admin Dashboard')


@section('content')

<!-- Responsive Title -->
<p>Logged in as: <strong>{{ Auth::user()->name }}!</strong> </p>


<div class="dashboard-cards">
  <div class="card">
    <i class="ph ph-users"></i>
    <span>Manage Staff</span>
  </div>
  <div class="card">
    <i class="ph ph-user-circle"></i>
    <span>Staff Profile</span>
  </div>
  <div class="card">
    <i class="ph ph-handshake"></i>
    <span>Assignments</span>
  </div>
  <div class="card">
    <i class="ph ph-users-three"></i>
    <span>Service Users</span>
  </div>
  <div class="card">
    <i class="ph ph-clock"></i>
    <span>Timesheets</span>
  </div>
  <div class="card">
    <i class="ph ph-chart-bar"></i>
    <span>Reports</span>
  </div>
  <div class="card">
    <i class="ph ph-warning"></i>
    <span>Urgent Cases</span>
  </div>
</div>
@endsection
