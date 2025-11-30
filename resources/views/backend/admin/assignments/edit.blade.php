@extends('layouts.admin')

@section('title', 'Edit Assignment')

@section('content')
  @php
    $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y · H:i') : '—';

    $badge = match ($assignment->status) {
        'scheduled', 'overdue'   => 'bg-yellow-100 text-yellow-800',
        'in_progress'            => 'bg-blue-100 text-blue-800',
        'submitted'              => 'bg-purple-100 text-purple-800',
        'verified', 'closed'     => 'bg-green-100 text-green-800',
        default                  => 'bg-gray-100 text-gray-800',
    };

    $dot = match ($assignment->status) {
        'scheduled', 'overdue'   => 'bg-yellow-500',
        'in_progress'            => 'bg-blue-500',
        'submitted'              => 'bg-purple-500',
        'verified', 'closed'     => 'bg-green-500',
        default                  => 'bg-gray-500',
    };
  @endphp

  <div class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
            Edit Assignment
          </h1>
          <p class="text-sm sm:text-base text-gray-600">
            Update assignment details, routing and requirements.
          </p>
        </div>

        <a href="{{ route('backend.admin.assignments.index', $assignment) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to details</span>
        </a>
      </div>

      {{-- Assignment summary card --}}
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-blue-200">
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0">
            {{ strtoupper(substr($assignment->title ?? 'A', 0, 1)) }}
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 break-words mb-1">
              {{ $assignment->title }}
            </h2>

            <div class="mt-6 flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white/70 rounded-full border border-blue-100">
                <i class="ph ph-clipboard-text text-blue-600"></i>
                <span class="font-medium">{{ ucfirst($assignment->type) }}</span>
              </span>

              @if($assignment->code)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white/70 rounded-full border border-blue-100">
                  <i class="ph ph-hash"></i>
                  <span>{{ $assignment->code }}</span>
                </span>
              @endif

              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $badge }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                <span class="font-medium">
                  {{ str_replace('_', ' ', ucfirst($assignment->status)) }}
                </span>
              </span>

              @if($assignment->due_at)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white/70 rounded-full border border-blue-100">
                  <i class="ph ph-calendar"></i>
                  <span>Due {{ $fmtDateTime($assignment->due_at) }}</span>
                </span>
              @endif
            </div>

            <div class="mt-2 text-xs sm:text-sm text-gray-500 space-x-2">
              <span>
                Resident:
                <strong>{{ optional($assignment->resident)->full_name ?? '—' }}</strong>
              </span>
              <span>•</span>
              <span>
                Location:
                <strong>{{ optional($assignment->location)->name ?? '—' }}</strong>
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
          <span>Assignment Details</span>
        </h2>
      </div>

      <form method="POST"
            action="{{ route('backend.admin.assignments.update', $assignment) }}"
            class="p-4 sm:p-5 lg:p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Top grid: Title, Type, Priority --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
          {{-- Title --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
            <input
              type="text"
              name="title"
              value="{{ old('title', $assignment->title) }}"
              required
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 ring-1 ring-red-300 @enderror"
            >
            @error('title')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Type --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Type</label>
            @php
              $types = [
                'care' => 'Care',
                'documentation' => 'Documentation',
                'operations' => 'Operations',
                'training' => 'Training',
              ];
            @endphp
            <select
              name="type"
              required
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 ring-1 ring-red-300 @enderror"
            >
              @foreach($types as $value => $label)
                <option value="{{ $value }}" {{ old('type', $assignment->type) === $value ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('type')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Priority --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Priority</label>
            @php
              $priorities = ['' => 'Normal', 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
            @endphp
            <select
              name="priority"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 ring-1 ring-red-300 @enderror"
            >
              @foreach($priorities as $value => $label)
                <option value="{{ $value }}" {{ old('priority', $assignment->priority) === $value ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('priority')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Location / Resident / Assigned To --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
          {{-- Location --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
            <select
              name="location_id"
              required
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_id') border-red-500 ring-1 ring-red-300 @enderror"
            >
              <option value="">Select location...</option>
              @foreach($locations as $location)
                <option value="{{ $location->id }}"
                  {{ (int) old('location_id', $assignment->location_id) === $location->id ? 'selected' : '' }}>
                  {{ $location->name }}
                </option>
              @endforeach
            </select>
            @error('location_id')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Resident --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Resident (optional)</label>
            <select
              name="resident_id"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('resident_id') border-red-500 ring-1 ring-red-300 @enderror"
            >
              <option value="">No specific resident</option>
              @foreach($residents as $resident)
                <option value="{{ $resident->id }}"
                  {{ (int) old('resident_id', $assignment->resident_id) === $resident->id ? 'selected' : '' }}>
                  {{ $resident->full_name ?? ($resident->first_name . ' ' . $resident->last_name) }}
                </option>
              @endforeach
            </select>
            @error('resident_id')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Assigned To --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Assigned To</label>
            <select
              name="assigned_to"
              required
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assigned_to') border-red-500 ring-1 ring-red-300 @enderror"
            >
              <option value="">Select staff...</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}"
                  {{ (int) old('assigned_to', $assignment->assigned_to) === $user->id ? 'selected' : '' }}>
                  {{ $user->full_name }}
                </option>
              @endforeach
            </select>
            @error('assigned_to')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Window Start / End / Due At --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
          {{-- Window Start --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Window Start</label>
            <input
              type="datetime-local"
              name="window_start"
              value="{{ old('window_start', optional($assignment->window_start)->format('Y-m-d\TH:i')) }}"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('window_start') border-red-500 ring-1 ring-red-300 @enderror"
            >
            @error('window_start')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Window End --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Window End</label>
            <input
              type="datetime-local"
              name="window_end"
              value="{{ old('window_end', optional($assignment->window_end)->format('Y-m-d\TH:i')) }}"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('window_end') border-red-500 ring-1 ring-red-300 @enderror"
            >
            @error('window_end')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Due At --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Due At</label>
            <input
              type="datetime-local"
              name="due_at"
              value="{{ old('due_at', optional($assignment->due_at)->format('Y-m-d\TH:i')) }}"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('due_at') border-red-500 ring-1 ring-red-300 @enderror"
            >
            @error('due_at')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Requirements (checkboxes) + SLA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
          <div class="space-y-2">
            <span class="block text-sm font-medium text-gray-700 mb-1">Requirements</span>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input
                type="checkbox"
                name="requires_gps"
                value="1"
                class="rounded border border-gray-300 text-blue-600 focus:ring-blue-500"
                {{ old('requires_gps', $assignment->requires_gps) ? 'checked' : '' }}
              >
              <span>Requires GPS check-in</span>
            </label>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input
                type="checkbox"
                name="requires_signature"
                value="1"
                class="rounded border border-gray-300 text-blue-600 focus:ring-blue-500"
                {{ old('requires_signature', $assignment->requires_signature) ? 'checked' : '' }}
              >
              <span>Requires signature</span>
            </label>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input
                type="checkbox"
                name="requires_photo"
                value="1"
                class="rounded border border-gray-300 text-blue-600 focus:ring-blue-500"
                {{ old('requires_photo', $assignment->requires_photo) ? 'checked' : '' }}
              >
              <span>Requires photo evidence</span>
            </label>
          </div>

          {{-- SLA --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">SLA (minutes)</label>
            <input
              type="number"
              name="sla_minutes"
              min="0"
              value="{{ old('sla_minutes', $assignment->sla_minutes) }}"
              class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sla_minutes') border-red-500 ring-1 ring-red-300 @enderror"
            >
            @error('sla_minutes')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Recurrence rule --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Recurrence Rule (optional)</label>
          <input
            type="text"
            name="recurrence_rule"
            value="{{ old('recurrence_rule', $assignment->recurrence_rule) }}"
            placeholder="e.g. FREQ=DAILY;INTERVAL=1"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('recurrence_rule') border-red-500 ring-1 ring-red-300 @enderror"
          >
          @error('recurrence_rule')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-2 border-t border-gray-100 mt-2">
          <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 shadow-sm hover:shadow-md transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-check-circle"></i>
            <span>Save changes</span>
          </button>

          {{-- Delete button --}}
          <button
            type="submit"
            form="delete-assignment-form"
            onclick="return confirm('Are you sure you want to delete this assignment?');"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 border border-red-200 text-sm sm:text-base font-medium">
            <i class="ph ph-trash"></i>
            <span>Delete assignment</span>
          </button>
        </div>
      </form>

      {{-- Separate Delete Form --}}
      <form
        id="delete-assignment-form"
        method="POST"
        action="{{ route('backend.admin.assignments.destroy', $assignment) }}"
        class="hidden">
        @csrf
        @method('DELETE')
      </form>
    </div>
  </div>
@endsection
