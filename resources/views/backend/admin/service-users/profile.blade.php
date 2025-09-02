@extends('layouts.admin')

@section('title', 'Service User Details')

@section('content')
  {{-- Page Header --}}
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-semibold leading-tight">
        {{ $su->first_name }} {{ $su->last_name }}
      </h1>
      <p class="text-sm text-gray-500">
        DOB: {{ optional($su->date_of_birth)->format('d M Y') ?? '—' }}
        @if(!empty($su->postcode))
          • Postcode: {{ $su->postcode }}
        @endif
      </p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('backend.admin.service-users.edit', $su->id) }}"
         class="px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
        Edit
      </a>
      <a href="{{ route('backend.admin.service-users.index') }}"
         class="px-3 py-2 text-sm border rounded hover:bg-gray-50">
        Back
      </a>
    </div>
  </div>

  {{-- Flash Messages --}}
  @if (session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <strong>Please fix the following:</strong>
      <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Summary Cards (quick context) --}}
  <div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-500 mb-1">Status</div>
      <div>
        <span class="px-2 py-1 rounded text-white
          @if($su->status === 'active') bg-green-600
          @elseif($su->status === 'discharged') bg-amber-600
          @else bg-gray-600 @endif">
          {{ ucfirst($su->status) }}
        </span>
      </div>
    </div>

    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-500 mb-1">Admission Date</div>
      <div>{{ optional($su->admission_date)->format('d M Y') ?? '—' }}</div>
    </div>

    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-500 mb-1">Location</div>
      <div>
        @if($su->relationLoaded('location') && $su->location)
          {{ $su->location->name }}
          @if($su->location->city) • {{ $su->location->city }} @endif
          @if($su->location->postcode) • {{ $su->location->postcode }} @endif
        @else
          —
        @endif
      </div>
    </div>
  </div>

  {{-- Tabs Nav (anchor links; simple + copyable for more tabs later) --}}
  <nav class="mb-4 flex flex-wrap gap-2">
    <a href="#identity" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Identity & Inclusion</a>
    <a href="#communication" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Communication</a>
    <a href="#clinical-flags" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Clinical Flags</a>
    <a href="#baselines" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Baselines</a>
    <a href="#risks" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Risks & Safeguarding</a>
    <a href="#legal-consent" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Legal & Consent</a>
    <a href="#preferences" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Preferences</a>
    <a href="#gp-pharmacy" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">GP & Pharmacy</a>
    <a href="#tags" class="px-3 py-2 bg-blue-100 rounded hover:bg-gray-200">Tags</a>
  </nav>

  {{-- SECTION: Identity & Inclusion --}}
  <section id="identity" class="scroll-mt-24">
    <h3 class="text-lg font-semibold mb-2">Identity & Inclusion</h3>

    {{-- Uses the section-based PATCH endpoint and the shared UpdateServiceUserRequest (section=identity) --}}
    @include('backend.admin.service-users.tabs._identity', ['su' => $su])
  </section>

  {{-- Stubs for future sections (copy pattern as you implement) --}}

  <section id="communication" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Communication</h3>
    @include('backend.admin.service-users.tabs._communication', ['su' => $su])
  </section>

  <section id="clinical-flags" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Clinical Flags & Plans</h3>
    @include('backend.admin.service-users.tabs._clinical_flags', ['su' => $su])
  </section>

  <section id="baselines" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Health Baselines</h3>
    @include('backend.admin.service-users.tabs._baselines', ['su' => $su])
  </section>

  <section id="risks" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Risks & Safeguarding</h3>
    @include('backend.admin.service-users.tabs._risks', ['su' => $su])
  </section>

  <section id="legal-consent" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Legal & Consent</h3>
    @include('backend.admin.service-users.tabs._legal_consent', ['su' => $su])
  </section>

  <section id="preferences" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Preferences</h3>
    @include('backend.admin.service-users.tabs._preferences', ['su' => $su])
  </section>

  <section id="gp-pharmacy" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">GP & Pharmacy</h3>
    @include('backend.admin.service-users.tabs._gp_pharmacy', ['su' => $su])
  </section>

  <section id="tags" class="scroll-mt-24 mt-8">
    <h3 class="text-lg font-semibold mb-2">Tags</h3>
    @include('backend.admin.service-users.tabs._tags', ['su' => $su])
  </section>
@endsection
