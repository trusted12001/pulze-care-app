@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')

@section('content')
<p>Logged in as: <strong>{{ Auth::user()->full_name }}!</strong></p>

<div class="dashboard-cards">

  <a href="{{ route('backend.super-admin.users.index') }}">
    <div class="card">
        <i class="ph ph-users"></i>
        <span>Manage Users</span>
    </div>
  </a>

  <a href="{{ route('backend.super-admin.tenants.index') }}">
    <div class="card">
        <i class="ph ph-buildings"></i>
        <span>Manage Tenants</span>
    </div>
  </a>

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

  <div class="card">
    <i class="ph ph-lock-key"></i>
    <span>Feature Access</span>
  </div>

  <div class="card">
    <i class="ph ph-paint-brush-broad"></i>
    <span>UI Customization</span>
  </div>

  <div class="card">
    <i class="ph ph-clipboard-text"></i>
    <span>Audit Logs</span>
  </div>

  <div class="card">
    <i class="ph ph-lifebuoy"></i>
    <span>Support Tickets</span>
  </div>

  <div class="card">
    <i class="ph ph-lightbulb"></i>
    <span>Ideas Portal</span>
  </div>

  <div class="card">
    <i class="ph ph-book-bookmark"></i>
    <span>Knowledge Base</span>
  </div>

  <div class="card">
    <i class="ph ph-gear"></i>
    <span>Platform Settings</span>
  </div>

    <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('signout-form').submit();">
        <div class="card">
            <i class="ph ph-sign-out logout-form"></i>
            <span>Log Out</span>
        </div>
    </a>

  <form id="signout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

</div>
@endsection
