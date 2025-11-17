@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

  <div class="min-h-screen p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Dashboard</h1>
      <p class="text-gray-600">
        Welcome back, <span class="font-semibold">{{ Auth::user()->full_name }}</span>
      </p>
    </div>

    {{-- Quick Actions Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">

      {{-- Active Links --}}
      <a href="{{ route('backend.admin.users.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-500 transition-colors">
            <i class="ph ph-users text-blue-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Manage Staff</h3>
            <p class="text-sm text-gray-500">Staff accounts</p>
          </div>
        </div>
      </a>

      <a href="{{ route('backend.admin.staff-profiles.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-indigo-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-500 transition-colors">
            <i class="ph ph-user-circle text-indigo-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Staff Profile</h3>
            <p class="text-sm text-gray-500">Staff profiles</p>
          </div>
        </div>
      </a>

      <a href="{{ route('backend.admin.locations.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-purple-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-500 transition-colors">
            <i class="ph ph-map-pin text-purple-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Location Setup</h3>
            <p class="text-sm text-gray-500">Manage locations</p>
          </div>
        </div>
      </a>

      <a href="{{ route('backend.admin.service-users.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-green-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-500 transition-colors">
            <i class="ph ph-users-three text-green-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Service Users</h3>
            <p class="text-sm text-gray-500">Service users</p>
          </div>
        </div>
      </a>

      <a href="{{ url('/assignments') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-teal-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-500 transition-colors">
            <i class="ph ph-handshake text-teal-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Assignments</h3>
            <p class="text-sm text-gray-500">Manage assignments</p>
          </div>
        </div>
      </a>

      <a href="{{ route('backend.admin.care-plans.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-red-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-500 transition-colors">
            <i class="ph ph-first-aid-kit text-red-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Care Plan</h3>
            <p class="text-sm text-gray-500">Care plans</p>
          </div>
        </div>
      </a>

      <a href="{{ route('backend.admin.risk-assessments.index') }}"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:border-orange-500 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-500 transition-colors">
            <i class="ph ph-shield-warning text-orange-600 group-hover:text-white text-xl transition-colors"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">Risk Assessment</h3>
            <p class="text-sm text-gray-500">Risk assessments</p>
          </div>
        </div>
      </a>

      {{-- Disabled/Coming Soon Items --}}
      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-clock-afternoon text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Shift Rota</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-clock text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Timesheets</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-chart-bar text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Reports</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-chart-pie-slice text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Statistics</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-credit-card text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Subscriptions</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-receipt text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Payment History</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-buildings text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Configure Locations</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-gear-six text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">System Settings</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-book-open text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Knowledge Base</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-lightbulb text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Ideas Portal</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 opacity-60">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
            <i class="ph ph-bell text-gray-400 text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-600 mb-1">Notifications</h3>
            <p class="text-sm text-gray-400">Coming soon</p>
          </div>
        </div>
      </div>

    </div>

  </div>

@endsection