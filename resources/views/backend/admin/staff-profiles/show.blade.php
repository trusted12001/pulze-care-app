@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Staff Details</h2>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md space-y-5">

    {{-- Identity --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Name</h4>
        <p class="text-lg font-semibold text-gray-800">
          {{ $staffProfile->user->full_name ?? '—' }}
        </p>
      </div>

      <div>
        <h4 class="text-sm text-gray-500">Email</h4>
        <p class="text-gray-700">{{ $staffProfile->user->email ?? '—' }}</p>
      </div>

      <div>
        <h4 class="text-sm text-gray-500">Job Title</h4>
        <p class="text-gray-700">{{ $staffProfile->job_title ?? '—' }}</p>
      </div>

      <div>
        <h4 class="text-sm text-gray-500">Employment Status</h4>
        @php
          $st = $staffProfile->employment_status ?? 'unknown';
          $badge = match($st) {
            'active'   => 'bg-green-500',
            'on_leave' => 'bg-amber-500',
            'inactive' => 'bg-gray-500',
            default    => 'bg-gray-400',
          };
        @endphp
        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $badge }}">
          {{ ucfirst(str_replace('_',' ',$st)) }}
        </span>
      </div>


    <div>
        <h4 class="text-sm text-gray-500">Date of Birth</h4>
        <p class="text-gray-700">{{ optional($staffProfile->date_of_birth)->format('d M Y') ?? '—' }}</p>
    </div>

    </div>

    <hr class="border-gray-200">

    {{-- HR Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Employment Type</h4>
        <p class="text-gray-700">{{ ucfirst($staffProfile->employment_type ?? '—') }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Engagement Basis</h4>
        <p class="text-gray-700">{{ ucwords(str_replace('_',' ', $staffProfile->engagement_basis ?? '—')) }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Hire Date</h4>
        <p class="text-gray-700">{{ optional($staffProfile->hire_date)->format('d M Y') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Start in Post</h4>
        <p class="text-gray-700">{{ optional($staffProfile->start_in_post)->format('d M Y') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">End in Post</h4>
        <p class="text-gray-700">{{ optional($staffProfile->end_in_post)->format('d M Y') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Work Location</h4>
        <p class="text-gray-700">{{ optional($staffProfile->workLocation)->name ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Line Manager</h4>
        <p class="text-gray-700">{{ optional($staffProfile->lineManager)->full_name ?? '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    {{-- Contact --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Work Email</h4>
        <p class="text-gray-700">{{ $staffProfile->work_email ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Phone</h4>
        <p class="text-gray-700">{{ $staffProfile->phone ?? '—' }}</p>
      </div>
    </div>

    <hr class="border-gray-200">

    {{-- Compliance --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">DBS Number</h4>
        <p class="text-gray-700">{{ $staffProfile->dbs_number ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">DBS Issued At</h4>
        <p class="text-gray-700">{{ optional($staffProfile->dbs_issued_at)->format('d M Y') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">DBS Update Service</h4>
        <p class="text-gray-700">{{ ($staffProfile->dbs_update_service ?? false) ? 'Yes' : 'No' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Mandatory Training Completed</h4>
        <p class="text-gray-700">{{ optional($staffProfile->mandatory_training_completed_at)->format('d M Y H:i') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Right to Work Verified</h4>
        <p class="text-gray-700">{{ optional($staffProfile->right_to_work_verified_at)->format('d M Y H:i') ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">NMC PIN</h4>
        <p class="text-gray-700">{{ $staffProfile->nmc_pin ?? '—' }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">GPhC PIN</h4>
        <p class="text-gray-700">{{ $staffProfile->gphc_pin ?? '—' }}</p>
      </div>
    </div>

    @if(!empty($staffProfile->notes))
      <hr class="border-gray-200">
      <div>
        <h4 class="text-sm text-gray-500">Notes</h4>
        <p class="text-gray-700 whitespace-pre-line">{{ $staffProfile->notes }}</p>
      </div>
    @endif

    <hr class="border-gray-200">

    {{-- Meta --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h4 class="text-sm text-gray-500">Created</h4>
        <p class="text-gray-700">{{ optional($staffProfile->created_at)->format('d M Y H:i') }}</p>
      </div>
      <div>
        <h4 class="text-sm text-gray-500">Last Updated</h4>
        <p class="text-gray-700">{{ optional($staffProfile->updated_at)->format('d M Y H:i') }}</p>
      </div>
    </div>
  </div>

  <div class="mt-6 flex items-center justify-between">
    <a href="{{ route('backend.admin.staff-profiles.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
  </div>

</div>
@endsection
