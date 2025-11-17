@extends('layouts.admin')

@section('title', 'Staff Profile')

@section('content')
  @php
    $val = fn($v) => (isset($v) && $v !== '' && $v !== null ? $v : '—');
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
    $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y, h:i A') : '—';
    $role = ucfirst($user->getRoleNames()->first() ?? 'None');
  @endphp

  <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="flex-1">
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 break-words">
            {{ $user->full_name }}
          </h1>
          <div
            class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
            <span class="flex items-center gap-1.5 break-all">
              <i class="ph ph-envelope-simple flex-shrink-0"></i>
              <span class="break-all">{{ $user->email }}</span>
            </span>
            <span class="hidden sm:inline text-gray-300">•</span>
            <span class="flex items-center gap-1.5">
              <i class="ph ph-user-circle flex-shrink-0"></i>
              {{ $role }}
            </span>
            <span class="hidden sm:inline text-gray-300">•</span>
            <span class="flex items-center gap-1.5">
              <i class="ph ph-calendar flex-shrink-0"></i>
              <span class="whitespace-nowrap">Joined {{ $fmtDate($user->created_at) }}</span>
            </span>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
          <a href="{{ route('backend.admin.users.edit', $user->id) }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-pencil-simple"></i>
            <span>Edit Profile</span>
          </a>
          <a href="{{ route('backend.admin.users.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-arrow-left"></i>
            <span>Back to List</span>
          </a>
        </div>
      </div>

      {{-- Status Badge --}}
      <div class="inline-flex items-center">
        <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm
                        {{ $user->status === 'active'
    ? 'bg-green-100 text-green-800 border border-green-200'
    : 'bg-red-100 text-red-800 border border-red-200' }}">
          <span class="w-2 h-2 rounded-full {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
          {{ ucfirst($user->status) }}
        </span>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
          <span class="break-words">{{ session('success') }}</span>
        </div>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
        <div class="flex items-start gap-2 text-sm sm:text-base">
          <i class="ph ph-warning-circle text-red-600 mt-0.5 flex-shrink-0"></i>
          <div class="min-w-0 flex-1">
            <strong class="font-semibold block mb-1">Please fix the following:</strong>
            <ul class="list-disc ml-4 sm:ml-5 space-y-1">
              @foreach ($errors->all() as $error)
                <li class="break-words">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">

      {{-- Left Column - Main Info --}}
      <div class="lg:col-span-2 space-y-4 sm:space-y-5 lg:space-y-6">

        {{-- Personal Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-user text-blue-600 flex-shrink-0"></i>
              <span>Personal Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">First
                  Name</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($user->first_name) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Last
                  Name</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($user->last_name) }}</p>
              </div>
              <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Other
                  Names</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($user->other_names) }}</p>
              </div>
              <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Full
                  Name</label>
                <p class="text-base sm:text-lg lg:text-xl text-gray-900 font-semibold break-words">{{ $user->full_name }}
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- Account Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-key text-purple-600 flex-shrink-0"></i>
              <span>Account Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Email
                  Address</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-all">{{ $user->email }}</p>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Role</label>
                <span
                  class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs sm:text-sm font-medium">
                  <i class="ph ph-shield-check"></i>
                  {{ $role }}
                </span>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Account
                  Status</label>
                <span class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium
                                {{ $user->status === 'active'
    ? 'bg-green-100 text-green-800'
    : 'bg-red-100 text-red-800' }}">
                  <span
                    class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                  {{ ucfirst($user->status) }}
                </span>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Email
                  Verified</label>
                <span class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium
                                {{ $user->email_verified_at
    ? 'bg-green-100 text-green-800'
    : 'bg-gray-100 text-gray-800' }}">
                  <i class="ph {{ $user->email_verified_at ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                  {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Activity Timeline Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-clock text-gray-600 flex-shrink-0"></i>
              <span>Activity Timeline</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-start gap-3 sm:gap-4 pb-3 sm:pb-4 border-b border-gray-100 last:border-0">
                <div
                  class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                  <i class="ph ph-calendar-check text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs sm:text-sm font-medium text-gray-900">Account Created</p>
                  <p class="text-xs sm:text-sm text-gray-500 break-words">{{ $fmtDateTime($user->created_at) }}</p>
                </div>
              </div>
              @if($user->updated_at && $user->updated_at->ne($user->created_at))
                <div class="flex items-start gap-3 sm:gap-4 pb-3 sm:pb-4 border-b border-gray-100 last:border-0">
                  <div
                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="ph ph-pencil text-green-600 text-sm sm:text-base"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-900">Last Updated</p>
                    <p class="text-xs sm:text-sm text-gray-500 break-words">{{ $fmtDateTime($user->updated_at) }}</p>
                  </div>
                </div>
              @endif
              @if($user->email_verified_at)
                <div class="flex items-start gap-3 sm:gap-4">
                  <div
                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="ph ph-check-circle text-purple-600 text-sm sm:text-base"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-900">Email Verified</p>
                    <p class="text-xs sm:text-sm text-gray-500 break-words">{{ $fmtDateTime($user->email_verified_at) }}</p>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>

      </div>

      {{-- Right Column - Quick Stats & Actions --}}
      <div class="space-y-4 sm:space-y-5 lg:space-y-6">

        {{-- Quick Stats Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-chart-bar text-emerald-600 flex-shrink-0"></i>
              <span>Quick Stats</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
              <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                <p class="text-xl sm:text-2xl font-bold text-gray-900 break-words">{{ $fmtDate($user->created_at) }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Member Since</p>
              </div>
              <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                <p class="text-xl sm:text-2xl font-bold text-gray-900">
                  {{ number_format($user->created_at->diffInHours(now()) / 24, 1) }}
                </p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Days Active</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Quick Actions Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-lightning text-amber-600 flex-shrink-0"></i>
              <span>Quick Actions</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-2 sm:space-y-3">
              <a href="{{ route('backend.admin.users.edit', $user->id) }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-50 hover:bg-blue-100 active:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-pencil-simple text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Edit Profile</span>
              </a>
              <a href="mailto:{{ $user->email }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-green-50 hover:bg-green-100 active:bg-green-200 text-green-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-envelope text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Send Email</span>
              </a>
              <a href="{{ route('backend.admin.users.index') }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-arrow-left text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Back to List</span>
              </a>
            </div>
          </div>
        </div>

        {{-- System Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-slate-50 to-gray-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-info text-slate-600 flex-shrink-0"></i>
              <span>System Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">User ID:</span>
                <span class="font-medium text-gray-900 break-all">#{{ $user->id }}</span>
              </div>
              @if($user->tenant_id)
                <div class="flex justify-between items-center gap-2">
                  <span class="text-gray-500">Tenant ID:</span>
                  <span class="font-medium text-gray-900 break-all">#{{ $user->tenant_id }}</span>
                </div>
              @endif
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Created:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($user->created_at) }}</span>
              </div>
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Updated:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($user->updated_at) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

@endsection