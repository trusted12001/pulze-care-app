@php
  // expects: $staffProfile
  $baseClasses = 'inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md';
  $on  = 'bg-blue-600 text-white';
  $off = 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50';
@endphp

<nav class="mb-5 flex flex-wrap gap-2">
  {{-- Overview --}}
  <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}"
     class="{{ request()->routeIs('backend.admin.staff-profiles.show') ? $on : $off }} {{ $baseClasses }}">
    Overview
  </a>

  {{-- Contracts --}}
  <a href="{{ route('backend.admin.staff-profiles.contracts.index', $staffProfile) }}"
     class="{{ request()->routeIs('backend.admin.staff-profiles.contracts.*') ? $on : $off }} {{ $baseClasses }}">
    Contracts
    @isset($staffProfile->contracts_count)
      <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
        {{ $staffProfile->contracts_count }}
      </span>
    @endisset
  </a>

  {{-- Registrations --}}
  <a href="{{ route('backend.admin.staff-profiles.registrations.index', $staffProfile) }}"
     class="{{ request()->routeIs('backend.admin.staff-profiles.registrations.*') ? $on : $off }} {{ $baseClasses }}">
    Registrations
    @isset($staffProfile->registrations_count)
      <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
        {{ $staffProfile->registrations_count }}
      </span>
    @endisset
  </a>

  {{-- Employment Checks --}}
    <a href="{{ route('backend.admin.staff-profiles.employment-checks.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.employment-checks.*') ? $on : $off }} {{ $baseClasses }}">
        Employment Checks
        @isset($staffProfile->employment_checks_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->employment_checks_count }}
            </span>
        @endisset
    </a>

    {{-- Visas / RTW --}}
    <a href="{{ route('backend.admin.staff-profiles.visas.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.visas.*') ? $on : $off }} {{ $baseClasses }}">
        Visas / RTW
        @isset($staffProfile->visas_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->visas_count }}
            </span>
    @endisset
    </a>

    {{-- Training --}}
    <a href="{{ route('backend.admin.staff-profiles.training-records.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.training-records.*') ? $on : $off }} {{ $baseClasses }}">
        Training
        @isset($staffProfile->training_records_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->training_records_count }}
            </span>
        @endisset
    </a>

    {{-- Supervisions --}}
    <a href="{{ route('backend.admin.staff-profiles.supervisions.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.supervisions.*') ? $on : $off }} {{ $baseClasses }}">
        Supervisions
        @isset($staffProfile->supervisions_appraisals_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->supervisions_appraisals_count }}
            </span>
        @endisset
    </a>


    {{-- Qualifications --}}
    <a href="{{ route('backend.admin.staff-profiles.qualifications.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.qualifications.*') ? $on : $off }} {{ $baseClasses }}">
        Qualifications
        @isset($staffProfile->qualifications_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->qualifications_count }}
            </span>
        @endisset
    </a>


    {{-- Occ Health --}}
    <a href="{{ route('backend.admin.staff-profiles.occ-health.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.occ-health.*') ? $on : $off }} {{ $baseClasses }}">
        Occ Health
        @isset($staffProfile->occ_health_clearances_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->occ_health_clearances_count }}
            </span>
        @endisset
    </a>


    {{-- Immunisations --}}
    <a href="{{ route('backend.admin.staff-profiles.immunisations.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.immunisations.*') ? $on : $off }} {{ $baseClasses }}">
        Immunisations
        @isset($staffProfile->immunisations_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->immunisations_count }}
            </span>
        @endisset
    </a>



    {{-- Leave --}}
    <a href="{{ route('backend.admin.staff-profiles.leave-records.index', $staffProfile) }}"
    class="{{ request()->routeIs('backend.admin.staff-profiles.leave-records.*') || request()->routeIs('backend.admin.staff-profiles.leave-entitlements.*') ? $on : $off }} {{ $baseClasses }}">
    Leave
    @isset($staffProfile->leave_records_count)
        <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
        {{ $staffProfile->leave_records_count }}
        </span>
    @endisset
    </a>

    {{-- Availability --}}
    <a href="{{ route('backend.admin.staff-profiles.availability.index', $staffProfile) }}"
    class="{{ request()->routeIs('backend.admin.staff-profiles.availability.*') ? $on : $off }} {{ $baseClasses }}">
    Availability
    @isset($staffProfile->availability_preferences_count)
        <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
        {{ $staffProfile->availability_preferences_count }}
        </span>
    @endisset
    </a>

    {{-- Emergency --}}
    <a href="{{ route('backend.admin.staff-profiles.emergency-contacts.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.emergency-contacts.*') ? $on : $off }} {{ $baseClasses }}">
        Emergency
        @isset($staffProfile->emergency_contacts_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->emergency_contacts_count }}
            </span>
        @endisset
    </a>

    {{-- Equality --}}
    <a href="{{ route('backend.admin.staff-profiles.equality.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.equality.*') ? $on : $off }} {{ $baseClasses }}">
        Equality
        @isset($staffProfile->equality_data_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->equality_data_count }}
            </span>
        @endisset
    </a>

    {{-- Adjustments --}}
    <a href="{{ route('backend.admin.staff-profiles.adjustments.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.adjustments.*') ? $on : $off }} {{ $baseClasses }}">
        Adjustments
        @isset($staffProfile->adjustments_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->adjustments_count }}
            </span>
        @endisset
    </a>

    {{-- Driving --}}
    <a href="{{ route('backend.admin.staff-profiles.driving-licences.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.driving-licences.*') ? $on : $off }} {{ $baseClasses }}">
        Driving
        @isset($staffProfile->driving_licences_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->driving_licences_count }}
            </span>
        @endisset
    </a>

    {{-- Disciplinary --}}
    <a href="{{ route('backend.admin.staff-profiles.disciplinary.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.disciplinary.*') ? $on : $off }} {{ $baseClasses }}">
        Disciplinary
        @isset($staffProfile->disciplinary_records_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->disciplinary_records_count }}
            </span>
        @endisset
    </a>

    {{-- Documents --}}
    <a href="{{ route('backend.admin.staff-profiles.documents.index', $staffProfile) }}"
        class="{{ request()->routeIs('backend.admin.staff-profiles.documents.*') ? $on : $off }} {{ $baseClasses }}">
        Documents
        @isset($staffProfile->documents_count)
            <span class="inline-flex items-center justify-center text-xs rounded-full bg-white/20 px-2 py-0.5">
            {{ $staffProfile->documents_count }}
            </span>
        @endisset
    </a>





</nav>
