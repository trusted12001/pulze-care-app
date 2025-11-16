@extends('layouts.admin')

@section('title', 'Edit Service User')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y') : '—';
    $badge = match ($su->status) {
      'active' => 'bg-green-100 text-green-800',
      'discharged' => 'bg-amber-100 text-amber-800',
      'on_leave' => 'bg-blue-100 text-blue-800',
      default => 'bg-gray-100 text-gray-800',
    };
  @endphp

  <div class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Edit Service User</h1>
          <p class="text-sm sm:text-base text-gray-600">Update service user information</p>
        </div>
        <a href="{{ route('backend.admin.service-users.index') }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to List</span>
        </a>
      </div>

      {{-- Service User Info Card --}}
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-blue-200">
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0">
            {{ strtoupper(substr($su->first_name ?? 'U', 0, 1)) }}
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 break-words mb-3">{{ $su->full_name }}</h2>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
              @if($su->date_of_birth)
                <span
                  class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">
                  <i class="ph ph-calendar"></i>
                  DOB: {{ $fmtDate($su->date_of_birth) }}
                </span>
              @endif
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                <span
                  class="w-1.5 h-1.5 rounded-full {{ $su->status === 'active' ? 'bg-green-500' : ($su->status === 'discharged' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
                {{ ucfirst($su->status) }}
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
          <span>Service User Information</span>
        </h2>
      </div>

      <form method="POST" action="{{ route('backend.admin.service-users.update', $su) }}" class="p-4 sm:p-5 lg:p-6">
        @csrf
        @method('PUT')
        @include('backend.admin.service-users._form', ['su' => $su, 'locations' => $locations])
      </form>
    </div>

  </div>
@endsection