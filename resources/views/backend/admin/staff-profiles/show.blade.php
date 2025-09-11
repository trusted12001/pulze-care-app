@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
@php
  use Illuminate\Support\Str;

  // Defensive: coalesce missing relationships to empty collections / nulls
  $contracts         = $staffProfile->contracts ?? collect();
  $payroll           = $staffProfile->payroll ?? null;
  $bankAccounts      = $staffProfile->bankAccounts ?? collect();
  $registrations     = $staffProfile->registrations ?? collect();
  $employmentChecks  = $staffProfile->employmentChecks ?? collect();
  $visas             = $staffProfile->visas ?? collect();
  $trainingRecords   = $staffProfile->trainingRecords ?? collect();
  $supervisions      = $staffProfile->supervisionsAppraisals ?? collect();
  $qualifications    = $staffProfile->qualifications ?? collect();
  $occHealth         = $staffProfile->occHealthClearances ?? collect();
  $immunisations     = $staffProfile->immunisations ?? collect();
  $leaveEntitlements = $staffProfile->leaveEntitlements ?? collect();
  $leaveRecords      = $staffProfile->leaveRecords ?? collect();
  $availability      = $staffProfile->availabilityPreferences ?? collect();
  $emergencyContacts = $staffProfile->emergencyContacts ?? collect();
  $equalityRaw       = $staffProfile->equalityData ?? null;
  $equalityRows      = $equalityRaw instanceof \Illuminate\Support\Collection ? $equalityRaw : ($equalityRaw ? collect([$equalityRaw]) : collect());
  $adjustments       = $staffProfile->adjustments ?? collect();
  $drivingLicences   = $staffProfile->drivingLicences ?? collect();
  $disciplinary      = $staffProfile->disciplinaryRecords ?? collect();
  $documents         = $staffProfile->documents ?? collect();

  $dow = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
@endphp

<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Staff Details</h2>
  </div>

  {{-- Tabs (keep) --}}
  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  {{-- =========================
       Core Profile (existing)
       ========================= --}}
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

  {{-- =========================
       Extended Modules (new)
       ========================= --}}

  {{-- Contracts --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Contracts</h3>
      @if (Route::has('backend.admin.staff-profiles.contracts.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.contracts.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($contracts->isEmpty())
      <p class="text-gray-500">No contracts recorded.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Ref</th>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">Hours/Wk</th>
              <th class="px-4 py-2">Start</th>
              <th class="px-4 py-2">End</th>
              <th class="px-4 py-2">FTE Salary</th>
              <th class="px-4 py-2">Hourly</th>
              <th class="px-4 py-2">Cost Centre</th>
            </tr>
          </thead>
          <tbody>
            @foreach($contracts as $c)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $c->contract_ref ?? '—' }}</td>
                <td class="px-4 py-2">{{ ucfirst(str_replace('_',' ',$c->contract_type ?? '—')) }}</td>
                <td class="px-4 py-2">{{ $c->hours_per_week ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($c->start_date)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($c->end_date)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ $c->fte_salary ? number_format($c->fte_salary,2) : '—' }}</td>
                <td class="px-4 py-2">{{ $c->hourly_rate ? number_format($c->hourly_rate,2) : '—' }}</td>
                <td class="px-4 py-2">{{ $c->cost_centre ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Payroll & Bank Accounts --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Payroll</h3>
        @if (Route::has('backend.admin.staff-profiles.payroll.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.payroll.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if(!$payroll)
        <p class="text-gray-500">No payroll details recorded.</p>
      @else
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div><dt class="text-gray-500">NI Number</dt><dd class="text-gray-800">{{ $payroll->ni_number ? '••••'.substr($payroll->ni_number, -4) : '—' }}</dd></div>
          <div><dt class="text-gray-500">Tax Code</dt><dd class="text-gray-800">{{ $payroll->tax_code ?? '—' }}</dd></div>
          <div><dt class="text-gray-500">Starter Declaration</dt><dd class="text-gray-800">{{ strtoupper($payroll->starter_declaration ?? '—') }}</dd></div>
          <div><dt class="text-gray-500">Student Loan</dt><dd class="text-gray-800">{{ ucfirst(str_replace('_',' ', $payroll->student_loan_plan ?? 'none')) }}</dd></div>
          <div><dt class="text-gray-500">Postgrad Loan</dt><dd class="text-gray-800">{{ ($payroll->postgrad_loan ?? false) ? 'Yes' : 'No' }}</dd></div>
          <div><dt class="text-gray-500">Payroll #</dt><dd class="text-gray-800">{{ $payroll->payroll_number ?? '—' }}</dd></div>
        </dl>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Bank Accounts</h3>
        @if (Route::has('backend.admin.staff-profiles.bank-accounts.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.bank-accounts.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($bankAccounts->isEmpty())
        <p class="text-gray-500">No bank accounts saved.</p>
      @else
        <ul class="divide-y">
          @foreach($bankAccounts as $ba)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ $ba->account_holder }}</span> —
              Sort: {{ $ba->sort_code ? '••-••-'.substr(str_replace('-','',$ba->sort_code), -2) : '—' }},
              Acct: {{ $ba->account_number ? '••••'.substr($ba->account_number, -4) : '—' }}
              @if($ba->building_society_roll) <span class="text-gray-500"> (Roll: {{ $ba->building_society_roll }})</span>@endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- Employment Checks --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Employment Checks</h3>
      @if (Route::has('backend.admin.staff-profiles.employment-checks.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.employment-checks.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($employmentChecks->isEmpty())
      <p class="text-gray-500">No checks recorded.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">Result</th>
              <th class="px-4 py-2">Ref</th>
              <th class="px-4 py-2">Issued</th>
              <th class="px-4 py-2">Expires</th>
            </tr>
          </thead>
          <tbody>
            @foreach($employmentChecks as $ec)
              <tr class="border-t">
                <td class="px-4 py-2">{{ str_replace('_',' ', $ec->check_type) }}</td>
                <td class="px-4 py-2">{{ ucfirst($ec->result ?? '—') }}</td>
                <td class="px-4 py-2">{{ $ec->reference_no ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($ec->issued_at)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($ec->expires_at)->format('d M Y') ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Visas --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Visas</h3>
      @if (Route::has('backend.admin.staff-profiles.visas.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.visas.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($visas->isEmpty())
      <p class="text-gray-500">No visas on file.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">BRP</th>
              <th class="px-4 py-2">CoS</th>
              <th class="px-4 py-2">Issued</th>
              <th class="px-4 py-2">Expiry</th>
              <th class="px-4 py-2">Restriction</th>
            </tr>
          </thead>
          <tbody>
            @foreach($visas as $v)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $v->visa_type ?? '—' }}</td>
                <td class="px-4 py-2">{{ $v->brp_number ?? '—' }}</td>
                <td class="px-4 py-2">{{ $v->cos_number ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($v->issue_date)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($v->expiry_date)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ $v->hours_restriction ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Registrations --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Professional Registrations</h3>
      @if (Route::has('backend.admin.staff-profiles.registrations.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.registrations.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($registrations->isEmpty())
      <p class="text-gray-500">No registrations recorded.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Body</th>
              <th class="px-4 py-2">PIN</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">First Reg</th>
              <th class="px-4 py-2">Expires</th>
              <th class="px-4 py-2">Revalidation</th>
            </tr>
          </thead>
          <tbody>
            @foreach($registrations as $r)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $r->body }}</td>
                <td class="px-4 py-2">{{ $r->pin_number }}</td>
                <td class="px-4 py-2">{{ ucfirst($r->status ?? 'active') }}</td>
                <td class="px-4 py-2">{{ optional($r->first_registered_at)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($r->expires_at)->format('d M Y') ?? '—' }}</td>
                <td class="px-4 py-2">{{ optional($r->revalidation_due_at)->format('d M Y') ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Training & Supervisions --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Training Records</h3>
        @if (Route::has('backend.admin.staff-profiles.training-records.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.training-records.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($trainingRecords->isEmpty())
        <p class="text-gray-500">No training recorded.</p>
      @else
        <ul class="divide-y">
          @foreach($trainingRecords as $t)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ $t->module_title }}</span>
              <span class="text-gray-500"> ({{ $t->module_code }})</span> —
              {{ $t->provider ?? '—' }} •
              Completed: {{ optional($t->completed_at)->format('d M Y') ?? '—' }} •
              Valid until: {{ optional($t->valid_until)->format('d M Y') ?? '—' }}
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Supervisions & Appraisals</h3>
        @if (Route::has('backend.admin.staff-profiles.supervisions-appraisals.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.supervisions-appraisals.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($supervisions->isEmpty())
        <p class="text-gray-500">No supervisions/appraisals recorded.</p>
      @else
        <ul class="divide-y">
          @foreach($supervisions as $s)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ ucfirst(str_replace('_',' ', $s->type)) }}</span> —
              Held: {{ optional($s->held_at)->format('d M Y') ?? '—' }},
              Manager: {{ optional($s->manager?->full_name)->name ?? ($s->manager_user_id ?? '—') }},
              Next due: {{ optional($s->next_due_at)->format('d M Y') ?? '—' }}
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- Qualifications --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Qualifications</h3>
      @if (Route::has('backend.admin.staff-profiles.qualifications.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.qualifications.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($qualifications->isEmpty())
      <p class="text-gray-500">No qualifications recorded.</p>
    @else
      <ul class="divide-y">
        @foreach($qualifications as $qf)
          <li class="py-2 text-sm">
            <span class="font-medium">{{ $qf->level }} — {{ $qf->title }}</span>
            <span class="text-gray-500"> ({{ $qf->institution ?? '—' }})</span>
            <span class="ml-2">Awarded: {{ optional($qf->awarded_at)->format('d M Y') ?? '—' }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  {{-- Occupational Health & Immunisations --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Occupational Health</h3>
        @if (Route::has('backend.admin.staff-profiles.occ-health.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.occ-health.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($occHealth->isEmpty())
        <p class="text-gray-500">No OH clearance records.</p>
      @else
        <ul class="divide-y">
          @foreach($occHealth as $oh)
            <li class="py-2 text-sm">
              Cleared: <span class="font-medium">{{ ($oh->cleared_for_role ?? false) ? 'Yes' : 'No' }}</span> —
              Assessed: {{ optional($oh->assessed_at)->format('d M Y') ?? '—' }},
              Review due: {{ optional($oh->review_due_at)->format('d M Y') ?? '—' }}<br>
              <span class="text-gray-600">{{ Str::limit($oh->restrictions ?? 'No restrictions', 120) }}</span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Immunisations</h3>
        @if (Route::has('backend.admin.staff-profiles.immunisations.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.immunisations.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($immunisations->isEmpty())
        <p class="text-gray-500">No immunisations recorded.</p>
      @else
        <ul class="divide-y">
          @foreach($immunisations as $im)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ $im->vaccine }}</span>
              @if($im->dose) <span class="text-gray-500"> ({{ $im->dose }})</span>@endif
              — {{ optional($im->administered_at)->format('d M Y') ?? '—' }}
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- Leave & Availability --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Leave Entitlements</h3>
        @if (Route::has('backend.admin.staff-profiles.leave-entitlements.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($leaveEntitlements->isEmpty())
        <p class="text-gray-500">No entitlements set.</p>
      @else
        <ul class="divide-y">
          @foreach($leaveEntitlements as $le)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ optional($le->period_start)->format('d M Y') ?? '—' }} → {{ optional($le->period_end)->format('d M Y') ?? '—' }}</span>
              — Annual leave: {{ number_format((float)$le->annual_leave_days, 2) }} days
              @if($le->sick_pay_scheme) <span class="text-gray-500"> ({{ $le->sick_pay_scheme }})</span>@endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Leave Records</h3>
        @if (Route::has('backend.admin.staff-profiles.leave-records.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.leave-records.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($leaveRecords->isEmpty())
        <p class="text-gray-500">No leave records.</p>
      @else
        <ul class="divide-y">
          @foreach($leaveRecords as $lr)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ ucfirst($lr->type) }}</span> —
              {{ optional($lr->start_date)->format('d M Y') ?? '—' }} → {{ optional($lr->end_date)->format('d M Y') ?? '—' }}
              @if(!is_null($lr->hours)) <span class="text-gray-500"> ({{ number_format((float)$lr->hours, 2) }} h)</span>@endif
              @if($lr->reason) <span class="block text-gray-600">{{ Str::limit($lr->reason, 120) }}</span>@endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Availability Preferences</h3>
      @if (Route::has('backend.admin.staff-profiles.availability-preferences.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.availability-preferences.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($availability->isEmpty())
      <p class="text-gray-500">No availability preferences.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Day</th>
              <th class="px-4 py-2">From</th>
              <th class="px-4 py-2">To</th>
              <th class="px-4 py-2">Preference</th>
            </tr>
          </thead>
          <tbody>
            @foreach($availability as $av)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $dow[$av->day_of_week] ?? $av->day_of_week }}</td>
                <td class="px-4 py-2">{{ $av->available_from ?? '—' }}</td>
                <td class="px-4 py-2">{{ $av->available_to ?? '—' }}</td>
                <td class="px-4 py-2">{{ ucfirst($av->preference ?? 'okay') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Emergency & Equality --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Emergency Contacts</h3>
        @if (Route::has('backend.admin.staff-profiles.emergency-contacts.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.emergency-contacts.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($emergencyContacts->isEmpty())
        <p class="text-gray-500">No emergency contacts set.</p>
      @else
        <ul class="divide-y">
          @foreach($emergencyContacts as $ec)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ $ec->name }}</span> — {{ $ec->relationship ?? '—' }} •
              {{ $ec->phone ?? '—' }}
              @if($ec->email) <span class="text-gray-500"> • {{ $ec->email }}</span>@endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Equality, Diversity & Inclusion</h3>
        @if (Route::has('backend.admin.staff-profiles.equality.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.equality.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($equalityRows->isEmpty())
        <p class="text-gray-500">No EDI data recorded.</p>
      @else
        @foreach($equalityRows as $eq)
          <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm {{ !$loop->last ? 'mb-4 pb-4 border-b' : '' }}">
            <div><dt class="text-gray-500">Ethnicity</dt><dd class="text-gray-800">{{ $eq->ethnicity ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Religion</dt><dd class="text-gray-800">{{ $eq->religion ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Disability</dt><dd class="text-gray-800">{{ ($eq->disability ?? false) ? 'Yes' : 'No' }}</dd></div>
            <div><dt class="text-gray-500">Gender Identity</dt><dd class="text-gray-800">{{ $eq->gender_identity ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Sexual Orientation</dt><dd class="text-gray-800">{{ $eq->sexual_orientation ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Data Source</dt><dd class="text-gray-800">{{ Str::headline($eq->data_source ?? 'self_declared') }}</dd></div>
          </dl>
        @endforeach
      @endif
    </div>
  </div>

  {{-- Adjustments & Driving --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Adjustments</h3>
        @if (Route::has('backend.admin.staff-profiles.adjustments.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.adjustments.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($adjustments->isEmpty())
        <p class="text-gray-500">No adjustments recorded.</p>
      @else
        <ul class="divide-y">
          @foreach($adjustments as $ad)
            <li class="py-2 text-sm">
              <span class="font-medium">{{ $ad->type }}</span> —
              From: {{ optional($ad->in_place_from)->format('d M Y') ?? '—' }}<br>
              <span class="text-gray-600">{{ Str::limit($ad->notes ?? '—', 140) }}</span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-semibold text-gray-800">Driving Licences</h3>
        @if (Route::has('backend.admin.staff-profiles.driving-licences.index'))
          <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.driving-licences.index', $staffProfile) }}">Manage</a>
        @endif
      </div>
      @if($drivingLicences->isEmpty())
        <p class="text-gray-500">No driving licence details.</p>
      @else
        <ul class="divide-y">
          @foreach($drivingLicences as $dl)
            @php
              $masked = $dl->licence_number ? (strlen($dl->licence_number) > 4 ? str_repeat('•', strlen($dl->licence_number)-4).substr($dl->licence_number, -4) : $dl->licence_number) : '—';
            @endphp
            <li class="py-2 text-sm">
              <span class="font-medium"># {{ $masked }}</span> — {{ $dl->categories ?? '—' }},
              Expires: {{ optional($dl->expires_at)->format('d M Y') ?? '—' }},
              Business insurance: {{ ($dl->business_insurance_confirmed ?? false) ? 'Yes' : 'No' }}
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- Disciplinary --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Disciplinary</h3>
      @if (Route::has('backend.admin.staff-profiles.disciplinary.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.disciplinary.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($disciplinary->isEmpty())
      <p class="text-gray-500">No disciplinary records.</p>
    @else
      <ul class="divide-y">
        @foreach($disciplinary as $dr)
          <li class="py-2 text-sm">
            <span class="font-medium">{{ ucfirst($dr->type) }}</span> —
            Opened: {{ optional($dr->opened_at)->format('d M Y') ?? '—' }},
            Closed: {{ optional($dr->closed_at)->format('d M Y') ?? '—' }}<br>
            <span class="text-gray-600">{{ Str::limit($dr->summary, 140) }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  {{-- Documents --}}
  <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6 shadow-md">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-xl font-semibold text-gray-800">Documents</h3>
      @if (Route::has('backend.admin.staff-profiles.documents.index'))
        <a class="text-blue-600 hover:underline" href="{{ route('backend.admin.staff-profiles.documents.index', $staffProfile) }}">Manage</a>
      @endif
    </div>
    @if($documents->isEmpty())
      <p class="text-gray-500">No documents uploaded.</p>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-2">Category</th>
              <th class="px-4 py-2">Filename</th>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">Uploaded</th>
            </tr>
          </thead>
          <tbody>
            @foreach($documents as $d)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $d->category }}</td>
                <td class="px-4 py-2">
                  <a href="{{ asset('storage/'.$d->path) }}" target="_blank" class="text-blue-600 hover:underline">{{ $d->filename }}</a>
                </td>
                <td class="px-4 py-2">{{ $d->mime }}</td>
                <td class="px-4 py-2">{{ optional($d->created_at)->format('d M Y H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Footer actions --}}
  <div class="mt-6 flex items-center justify-between">
    <a href="{{ route('backend.admin.staff-profiles.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
  </div>

</div>
@endsection
