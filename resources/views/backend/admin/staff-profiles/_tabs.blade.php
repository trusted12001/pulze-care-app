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


</nav>
