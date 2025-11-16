@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y') : '—';
    $role = ucfirst($user->getRoleNames()->first() ?? 'None');
  @endphp

  <div class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Edit Staff Member</h1>
          <p class="text-sm sm:text-base text-gray-600">Update staff account information</p>
        </div>
        <a href="{{ route('backend.admin.users.index') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to List</span>
        </a>
      </div>

      {{-- User Info Card --}}
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-blue-200">
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0">
            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 break-words mb-3">{{ $user->full_name }}</h2>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
              <span
                class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">
                <i class="ph ph-shield-check"></i>
                {{ $role }}
              </span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                              {{ $user->status === 'active'
    ? 'bg-green-100 text-green-800'
    : 'bg-red-100 text-red-800' }}">
                <span
                  class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ ucfirst($user->status) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
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

    {{-- Edit Form --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-4 sm:px-5 lg:px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-pencil-simple text-gray-600"></i>
          <span>Account Information</span>
        </h2>
      </div>

      <form method="POST" action="{{ route('backend.admin.users.update', $user->id) }}" class="p-4 sm:p-5 lg:p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              First Name <span class="text-red-500">*</span>
            </label>
            <input name="first_name" value="{{ old('first_name', $user->first_name) }}" required
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
          </div>

          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Last Name <span class="text-red-500">*</span>
            </label>
            <input name="last_name" value="{{ old('last_name', $user->last_name) }}" required
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
          </div>

          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Other Names
            </label>
            <input name="other_names" value="{{ old('other_names', $user->other_names) }}"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
          </div>

          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Email <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
          </div>

          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Password
            </label>
            <input type="password" name="password" placeholder="Leave blank to keep current password"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
            <p class="mt-1 text-xs text-gray-500">Leave blank if you don't want to change the password</p>
          </div>

          <div>
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Status <span class="text-red-500">*</span>
            </label>
            <select name="status" required
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
              <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">
              Role <span class="text-red-500">*</span>
            </label>
            <select name="role" required
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
              @foreach($roles as $role)
                @if(in_array($role->name, ['admin', 'carer']))
                  <option value="{{ $role->name }}" {{ old('role', $user->hasRole($role->name) ? $role->name : '') === $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                  </option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        {{-- Account Info Section --}}
        <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-gray-200">
          <h3 class="text-sm sm:text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="ph ph-info text-gray-500"></i>
            <span>Account Details</span>
          </h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">User ID</label>
              <p class="text-sm sm:text-base font-medium text-gray-900">#{{ $user->id }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Member Since</label>
              <p class="text-sm sm:text-base font-medium text-gray-900">{{ $fmtDate($user->created_at) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Last Updated</label>
              <p class="text-sm sm:text-base font-medium text-gray-900">{{ $fmtDate($user->updated_at) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email Verified</label>
              <p class="text-sm sm:text-base font-medium text-gray-900">
                @if($user->email_verified_at)
                  <span class="inline-flex items-center gap-1 text-green-700">
                    <i class="ph ph-check-circle"></i>
                    Yes
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-gray-600">
                    <i class="ph ph-x-circle"></i>
                    No
                  </span>
                @endif
              </p>
            </div>
          </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
          <a href="{{ route('backend.admin.users.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-x"></i>
            <span>Cancel</span>
          </a>
          <button type="submit"
            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-check"></i>
            <span>Update Staff</span>
          </button>
        </div>
      </form>
    </div>

  </div>

@endsection