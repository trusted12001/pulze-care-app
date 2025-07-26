@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')

@section('content')
<p>Logged in as: <strong>{{ Auth::user()->name }}!</strong></p>

<div class="dashboard-cards">
  <div class="card">
    <i class="ph ph-buildings"></i>
    <span>Manage Tenants</span>
  </div>
  <div class="card">
    <i class="ph ph-identification-badge"></i>
    <span>Tenants Profile</span>
  </div>
  <div class="card">
    <i class="ph ph-puzzle-piece"></i>
    <span>Manage Modules</span>
  </div>
  <div class="card">
    <i class="ph ph-wallet"></i>
    <span>Subscriptions</span>
  </div>
  <div class="card">
    <i class="ph ph-bank"></i>
    <span>Transactions</span>
  </div>
  <div class="card">
    <i class="ph ph-chart-bar"></i>
    <span>Reports</span>
  </div>
</div>
@endsection
