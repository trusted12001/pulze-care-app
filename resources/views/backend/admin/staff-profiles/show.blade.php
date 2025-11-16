@extends('layouts.admin')

@section('title', 'Staff Profile')

@section('content')
  @php
    use Illuminate\Support\Str;

    // Defensive: coalesce missing relationships to empty collections / nulls
    $contracts = $staffProfile->contracts ?? collect();
    $payroll = $staffProfile->payroll ?? null;
    $bankAccounts = $staffProfile->bankAccounts ?? collect();
    $registrations = $staffProfile->registrations ?? collect();
    $employmentChecks = $staffProfile->employmentChecks ?? collect();
    $visas = $staffProfile->visas ?? collect();
    $trainingRecords = $staffProfile->trainingRecords ?? collect();
    $supervisions = $staffProfile->supervisionsAppraisals ?? collect();
    $qualifications = $staffProfile->qualifications ?? collect();
    $occHealth = $staffProfile->occHealthClearances ?? collect();
    $immunisations = $staffProfile->immunisations ?? collect();
    $leaveEntitlements = $staffProfile->leaveEntitlements ?? collect();
    $leaveRecords = $staffProfile->leaveRecords ?? collect();
    $availability = $staffProfile->availabilityPreferences ?? collect();
    $emergencyContacts = $staffProfile->emergencyContacts ?? collect();
    $equalityRaw = $staffProfile->equalityData ?? null;
    $equalityRows = $equalityRaw instanceof \Illuminate\Support\Collection ? $equalityRaw : ($equalityRaw ? collect([$equalityRaw]) : collect());
    $adjustments = $staffProfile->adjustments ?? collect();
    $drivingLicences = $staffProfile->drivingLicences ?? collect();
    $disciplinary = $staffProfile->disciplinaryRecords ?? collect();
    $documents = $staffProfile->documents ?? collect();

    $dow = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    $val = fn($v) => (isset($v) && $v !== '' && $v !== null ? $v : '—');
    $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
    $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y, h:i A') : '—';

    $user = $staffProfile->user;
    $st = $staffProfile->employment_status ?? 'unknown';
    $badge = match ($st) {
      'active' => 'bg-green-100 text-green-800 border border-green-200',
      'on_leave' => 'bg-amber-100 text-amber-800 border border-amber-200',
      'inactive' => 'bg-gray-100 text-gray-800 border border-gray-200',
      default => 'bg-gray-100 text-gray-800 border border-gray-200',
    };
  @endphp

  <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="flex-1">
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 break-words">
            {{ $user->full_name ?? '—' }}
          </h1>
          <div
            class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
            @if($user->email)
              <span class="flex items-center gap-1.5 break-all">
                <i class="ph ph-envelope-simple flex-shrink-0"></i>
                <span class="break-all">{{ $user->email }}</span>
              </span>
              <span class="hidden sm:inline text-gray-300">•</span>
            @endif
            @if($staffProfile->job_title)
              <span class="flex items-center gap-1.5">
                <i class="ph ph-briefcase flex-shrink-0"></i>
                {{ $staffProfile->job_title }}
              </span>
              <span class="hidden sm:inline text-gray-300">•</span>
            @endif
            <span class="flex items-center gap-1.5">
              <i class="ph ph-calendar flex-shrink-0"></i>
              <span class="whitespace-nowrap">Created {{ $fmtDate($staffProfile->created_at) }}</span>
            </span>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
          <a href="{{ route('backend.admin.staff-profiles.edit', $staffProfile->id) }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
            <i class="ph ph-pencil-simple"></i>
            <span>Edit Profile</span>
          </a>
          <a href="{{ route('backend.admin.staff-profiles.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
            <i class="ph ph-arrow-left"></i>
            <span>Back to List</span>
          </a>
        </div>
      </div>

      {{-- Status Badge --}}
      <div class="inline-flex items-center">
        <span
          class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm {{ $badge }}">
          <span
            class="w-2 h-2 rounded-full {{ $st === 'active' ? 'bg-green-500' : ($st === 'on_leave' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
          {{ ucfirst(str_replace('_', ' ', $st)) }}
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

    {{-- Tabs --}}
    @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6 mt-6">

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
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Name</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($user->full_name) }}</p>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Email</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-all">{{ $val($user->email) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Job
                  Title</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $val($staffProfile->job_title) }}
                </p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Date of
                  Birth</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($staffProfile->date_of_birth) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Work
                  Email</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium break-all">{{ $val($staffProfile->work_email) }}
                </p>
              </div>
              <div>
                <label
                  class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Phone</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($staffProfile->phone) }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Employment Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-briefcase text-purple-600 flex-shrink-0"></i>
              <span>Employment Information</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Employment
                  Status</label>
                <span
                  class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                  <span
                    class="w-1.5 h-1.5 rounded-full {{ $st === 'active' ? 'bg-green-500' : ($st === 'on_leave' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
                  {{ ucfirst(str_replace('_', ' ', $st)) }}
                </span>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Employment
                  Type</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ ucfirst($val($staffProfile->employment_type)) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Engagement
                  Basis</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ ucwords(str_replace('_', ' ', $val($staffProfile->engagement_basis))) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Hire
                  Date</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($staffProfile->hire_date) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Start in
                  Post</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($staffProfile->start_in_post) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">End in
                  Post</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($staffProfile->end_in_post) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Work
                  Location</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ $val(optional($staffProfile->workLocation)->name) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Line
                  Manager</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ $val(optional($staffProfile->lineManager)->full_name) }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Compliance Information Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-shield-check text-emerald-600 flex-shrink-0"></i>
              <span>Compliance & Registrations</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">DBS
                  Number</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($staffProfile->dbs_number) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">DBS Issued
                  At</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($staffProfile->dbs_issued_at) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">DBS Update
                  Service</label>
                <span
                  class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ ($staffProfile->dbs_update_service ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                  <i
                    class="ph {{ ($staffProfile->dbs_update_service ?? false) ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                  {{ ($staffProfile->dbs_update_service ?? false) ? 'Yes' : 'No' }}
                </span>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Mandatory
                  Training Completed</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ $fmtDateTime($staffProfile->mandatory_training_completed_at) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Right to
                  Work Verified</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">
                  {{ $fmtDateTime($staffProfile->right_to_work_verified_at) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">NMC
                  PIN</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($staffProfile->nmc_pin) }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GPhC
                  PIN</label>
                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($staffProfile->gphc_pin) }}</p>
              </div>
            </div>
          </div>
        </div>

        @if(!empty($staffProfile->notes))
          {{-- Notes Card --}}
          <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div
              class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
              <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="ph ph-note text-gray-600 flex-shrink-0"></i>
                <span>Notes</span>
              </h2>
            </div>
            <div class="p-4 sm:p-5 lg:p-6">
              <p class="text-sm sm:text-base text-gray-700 whitespace-pre-line">{{ $staffProfile->notes }}</p>
            </div>
          </div>
        @endif

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
                <p class="text-xl sm:text-2xl font-bold text-gray-900 break-words">
                  {{ $fmtDate($staffProfile->created_at) }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Profile Created</p>
              </div>
              @if($staffProfile->hire_date)
                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                  <p class="text-xl sm:text-2xl font-bold text-gray-900 break-words">
                    {{ $fmtDate($staffProfile->hire_date) }}</p>
                  <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Hire Date</p>
                </div>
              @endif
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
              <a href="{{ route('backend.admin.staff-profiles.edit', $staffProfile->id) }}"
                class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-50 hover:bg-blue-100 active:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-pencil-simple text-base sm:text-lg flex-shrink-0"></i>
                <span class="font-medium">Edit Profile</span>
              </a>
              @if($user->email)
                <a href="mailto:{{ $user->email }}"
                  class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-green-50 hover:bg-green-100 active:bg-green-200 text-green-700 rounded-lg transition-colors duration-200 text-sm sm:text-base font-medium">
                  <i class="ph ph-envelope text-base sm:text-lg flex-shrink-0"></i>
                  <span class="font-medium">Send Email</span>
                </a>
              @endif
              <a href="{{ route('backend.admin.staff-profiles.index') }}"
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
                <span class="text-gray-500">Profile ID:</span>
                <span class="font-medium text-gray-900 break-all">#{{ $staffProfile->id }}</span>
              </div>
              @if($staffProfile->tenant_id)
                <div class="flex justify-between items-center gap-2">
                  <span class="text-gray-500">Tenant ID:</span>
                  <span class="font-medium text-gray-900 break-all">#{{ $staffProfile->tenant_id }}</span>
                </div>
              @endif
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Created:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($staffProfile->created_at) }}</span>
              </div>
              <div class="flex justify-between items-center gap-2">
                <span class="text-gray-500">Updated:</span>
                <span class="font-medium text-gray-900 break-words">{{ $fmtDate($staffProfile->updated_at) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    {{-- Extended Modules Section --}}
    <div class="mt-6 sm:mt-8 space-y-4 sm:space-y-5 lg:space-y-6">

      {{-- Contracts --}}
      @if($contracts->isNotEmpty())
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="ph ph-file-text text-blue-600"></i>
                <span>Contracts</span>
              </h3>
              @if (Route::has('backend.admin.staff-profiles.contracts.index'))
                <a class="text-blue-600 hover:underline text-sm"
                  href="{{ route('backend.admin.staff-profiles.contracts.index', $staffProfile) }}">Manage</a>
              @endif
            </div>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
                  <tr>
                    <th class="px-4 py-2 text-left">Ref</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Hours/Wk</th>
                    <th class="px-4 py-2 text-left">Start</th>
                    <th class="px-4 py-2 text-left">End</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  @foreach($contracts as $c)
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-2">{{ $c->contract_ref ?? '—' }}</td>
                      <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $c->contract_type ?? '—')) }}</td>
                      <td class="px-4 py-2">{{ $c->hours_per_week ?? '—' }}</td>
                      <td class="px-4 py-2">{{ optional($c->start_date)->format('d M Y') ?? '—' }}</td>
                      <td class="px-4 py-2">{{ optional($c->end_date)->format('d M Y') ?? '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endif

      {{-- Summary Cards for other modules --}}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @if($payroll)
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Payroll</h3>
                @if (Route::has('backend.admin.staff-profiles.payroll.index'))
                  <a class="text-blue-600 hover:underline text-xs"
                    href="{{ route('backend.admin.staff-profiles.payroll.index', $staffProfile) }}">Manage</a>
                @endif
              </div>
            </div>
            <div class="p-4">
              <p class="text-xs text-gray-500 mb-2">NI Number</p>
              <p class="text-sm font-medium text-gray-900">
                {{ $payroll->ni_number ? '••••' . substr($payroll->ni_number, -4) : '—' }}</p>
            </div>
          </div>
        @endif

        @if($bankAccounts->isNotEmpty())
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Bank Accounts</h3>
                @if (Route::has('backend.admin.staff-profiles.bank-accounts.index'))
                  <a class="text-blue-600 hover:underline text-xs"
                    href="{{ route('backend.admin.staff-profiles.bank-accounts.index', $staffProfile) }}">Manage</a>
                @endif
              </div>
            </div>
            <div class="p-4">
              <p class="text-xs text-gray-500 mb-1">{{ $bankAccounts->count() }} account(s)</p>
              <p class="text-sm text-gray-700">View details to see account information</p>
            </div>
          </div>
        @endif

        @if($registrations->isNotEmpty())
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Registrations</h3>
                @if (Route::has('backend.admin.staff-profiles.registrations.index'))
                  <a class="text-blue-600 hover:underline text-xs"
                    href="{{ route('backend.admin.staff-profiles.registrations.index', $staffProfile) }}">Manage</a>
                @endif
              </div>
            </div>
            <div class="p-4">
              <p class="text-xs text-gray-500 mb-1">{{ $registrations->count() }} registration(s)</p>
              <p class="text-sm text-gray-700">View details to see registration information</p>
            </div>
          </div>
        @endif
      </div>

    </div>

  </div>
@endsection