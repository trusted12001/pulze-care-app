@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')

@section('content')


    {{-- Feedback Messages --}}
    @if(session('success'))
        <div
            class="mb-4 flex items-center gap-2 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md shadow-sm">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 flex gap-2 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md shadow-sm">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('signout-form').submit();">
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