@extends('layouts.admin')

@section('title', 'Admin Dashboard')


@section('content')

<!-- Responsive Title -->
<p>Logged in as: <strong>{{ Auth::user()->name }}!</strong> </p>


<div class="dashboard-cards">
  <!-- 1. Core Staff & Service User Management -->
  <a href="{{route('backend.admin.users.index')}}">
    <div class="card">
      <i class="ph ph-users"></i>
      <span>Manage Staff</span>
    </div>
  </a>
  <div class="card">
    <i class="ph ph-user-circle"></i>
    <span>Staff Profile</span>
  </div>
  <div class="card">
    <i class="ph ph-users-three"></i>
    <span>Service Users</span>
  </div>

  <!-- 2. Scheduling and Assignments -->
  <div class="card">
    <i class="ph ph-clock-afternoon"></i>
    <span>Shift Rota</span>
  </div>
  <div class="card">
    <i class="ph ph-handshake"></i>
    <span>Assignments</span>
  </div>
  <div class="card">
    <i class="ph ph-clock"></i>
    <span>Timesheets</span>
  </div>

  <!-- 3. Care & Compliance -->
  <div class="card">
    <i class="ph ph-first-aid-kit"></i>
    <span>Care Plan</span>
  </div>
  <div class="card">
    <i class="ph ph-shield-warning"></i>
    <span>Risk Assessment</span>
  </div>
  <div class="card">
    <i class="ph ph-thermometer"></i>
    <span>Health Info</span>
  </div>
  <div class="card">
    <i class="ph ph-warning"></i>
    <span>Urgent Cases</span>
  </div>

  <!-- 4. Insights & Statistics -->
  <div class="card">
    <i class="ph ph-chart-bar"></i>
    <span>Reports</span>
  </div>
  <div class="card">
    <i class="ph ph-chart-pie-slice"></i>
    <span>Statistics</span>
  </div>

  <!-- 5. Financial & Billing -->
  <div class="card">
    <i class="ph ph-credit-card"></i>
    <span>Subscriptions</span>
  </div>
  <div class="card">
    <i class="ph ph-receipt"></i>
    <span>Payment History</span>
  </div>

  <!-- 6. Configuration & Settings -->
  <div class="card">
    <i class="ph ph-buildings"></i>
    <span>Configure Locations</span>
  </div>
  <div class="card">
    <i class="ph ph-gear-six"></i>
    <span>System Settings</span>
  </div>

  <!-- 7. Support & Knowledge -->
  <div class="card">
    <i class="ph ph-book-open"></i>
    <span>Knowledge Base</span>
  </div>
  <div class="card">
    <i class="ph ph-lightbulb"></i>
    <span>Ideas Portal</span>
  </div>
  <div class="card">
    <i class="ph ph-bell"></i>
    <span>Notifications</span>
  </div>
</div>

@endsection
