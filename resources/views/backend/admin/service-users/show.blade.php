@extends('layouts.admin')

@section('title', 'Service User Profile')

@section('content')
@php
  // Safe helpers for display
  $val = fn($v) => (isset($v) && $v !== '' ? $v : '—');
  $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
  $fmtMoney = fn($n) => is_null($n) ? '—' : '£'.number_format($n, 2);
  $yesNo = fn($b) => $b ? 'Yes' : 'No';

  // Tags: support JSON array or CSV string
  $tags = [];
  if (is_string($su->tags) && trim($su->tags) !== '') {
      $trim = trim($su->tags);
      if (str_starts_with($trim, '[')) {
          $decoded = json_decode($trim, true);
          if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
              $tags = $decoded;
          }
      } else {
          $tags = array_filter(array_map('trim', explode(',', $trim)));
      }
  }

  // Full name fallback
  $fullName = trim(($su->first_name ?? '').' '.($su->middle_name ?? '').' '.($su->last_name ?? ''));
@endphp

<div class="max-w-5xl mx-auto py-10 print:py-0">

  {{-- Header --}}
  <div class="flex items-center justify-between mb-6 print:hidden">
    <h2 class="text-2xl font-bold text-gray-800">Service User Profile</h2>
    <div class="space-x-2">
      <a href="{{ route('backend.admin.service-users.index') }}" class="text-blue-600 hover:underline">← Back to List</a>
      <button onclick="window.print()" class="px-3 py-1 text-sm bg-gray-700 text-white rounded hover:bg-gray-900">🖨 Print</button>
    </div>
  </div>

  {{-- Card --}}
  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md space-y-8 print:border-0 print:shadow-none print:rounded-none">

    {{-- Identity & Demographics --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Identity & Demographics</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">First Name</h4><p class="text-gray-700">{{ $val($su->first_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Middle Name</h4><p class="text-gray-700">{{ $val($su->middle_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Last Name</h4><p class="text-gray-700">{{ $val($su->last_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Preferred Name</h4><p class="text-gray-700">{{ $val($su->preferred_name) }}</p></div>

        <div><h4 class="text-sm text-gray-500">Date of Birth</h4><p class="text-gray-700">{{ $fmtDate($su->date_of_birth) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Sex at Birth</h4><p class="text-gray-700">{{ $val($su->sex_at_birth) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Gender Identity</h4><p class="text-gray-700">{{ $val($su->gender_identity) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Pronouns</h4><p class="text-gray-700">{{ $val($su->pronouns) }}</p></div>

        <div><h4 class="text-sm text-gray-500">NHS Number</h4><p class="text-gray-700">{{ $val($su->nhs_number) }}</p></div>
        <div><h4 class="text-sm text-gray-500">National Insurance No</h4><p class="text-gray-700">{{ $val($su->national_insurance_no) }}</p></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">Photo Path / URL</h4><p class="text-gray-700 break-all">{{ $val($su->photo_path) }}</p></div>
      </div>
    </section>

    {{-- Contact & Address --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Contact & Address</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Primary Phone</h4><p class="text-gray-700">{{ $val($su->primary_phone) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Secondary Phone</h4><p class="text-gray-700">{{ $val($su->secondary_phone) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Email</h4><p class="text-gray-700 break-all">{{ $val($su->email) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Address Line 1</h4><p class="text-gray-700">{{ $val($su->address_line1) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Address Line 2</h4><p class="text-gray-700">{{ $val($su->address_line2) }}</p></div>
        <div><h4 class="text-sm text-gray-500">City</h4><p class="text-gray-700">{{ $val($su->city) }}</p></div>
        <div><h4 class="text-sm text-gray-500">County</h4><p class="text-gray-700">{{ $val($su->county) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Postcode</h4><p class="text-gray-700">{{ $val($su->postcode) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Country</h4><p class="text-gray-700">{{ $val($su->country) }}</p></div>
      </div>
    </section>

    {{-- Placement --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Placement</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Placement Type</h4><p class="text-gray-700">{{ $val($su->placement_type) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Location</h4><p class="text-gray-700">{{ optional($su->location)->name ?? '—' }}</p></div>
        <div><h4 class="text-sm text-gray-500">Room Number</h4><p class="text-gray-700">{{ $val($su->room_number) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Status</h4>
          @php $badge = $su->status === 'active' ? 'bg-green-600' : ($su->status === 'discharged' ? 'bg-gray-600' : 'bg-amber-600'); @endphp
          <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $badge }}">{{ $val(ucfirst($su->status)) }}</span>
        </div>
        <div><h4 class="text-sm text-gray-500">Admission Date</h4><p class="text-gray-700">{{ $fmtDate($su->admission_date) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Expected Discharge</h4><p class="text-gray-700">{{ $fmtDate($su->expected_discharge_date) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Discharge Date</h4><p class="text-gray-700">{{ $fmtDate($su->discharge_date) }}</p></div>
      </div>
    </section>

    {{-- Funding --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Funding</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Funding Type</h4><p class="text-gray-700">{{ $val($su->funding_type) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Funding Authority</h4><p class="text-gray-700">{{ $val($su->funding_authority) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Purchase Order Ref</h4><p class="text-gray-700">{{ $val($su->purchase_order_ref) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Weekly Rate</h4><p class="text-gray-700">{{ $fmtMoney($su->weekly_rate) }}</p></div>
      </div>
    </section>

    {{-- Health & Clinical --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Health & Clinical</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Primary Diagnosis</h4><p class="text-gray-700">{{ $val($su->primary_diagnosis) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Other Diagnoses</h4><p class="text-gray-700">{{ $val($su->other_diagnoses) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Allergies Summary</h4><p class="text-gray-700">{{ $val($su->allergies_summary) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Diet Type</h4><p class="text-gray-700">{{ $val($su->diet_type) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Intolerances</h4><p class="text-gray-700">{{ $val($su->intolerances) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Mobility Status</h4><p class="text-gray-700">{{ $val($su->mobility_status) }}</p></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">Communication Needs</h4><p class="text-gray-700 whitespace-pre-line">{{ $val($su->communication_needs) }}</p></div>
      </div>
    </section>

    {{-- Care Plans, Baselines & Risks --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Care Plans, Baselines & Risks</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Behaviour Support Plan</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->behaviour_support_plan ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->behaviour_support_plan) }}</span></div>
        <div><h4 class="text-sm text-gray-500">Seizure Care Plan</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->seizure_care_plan ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->seizure_care_plan) }}</span></div>
        <div><h4 class="text-sm text-gray-500">Diabetes Care Plan</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->diabetes_care_plan ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->diabetes_care_plan) }}</span></div>
        <div><h4 class="text-sm text-gray-500">Oxygen Therapy</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->oxygen_therapy ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->oxygen_therapy) }}</span></div>

        <div><h4 class="text-sm text-gray-500">Baseline BP</h4><p class="text-gray-700">{{ $val($su->baseline_bp) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Baseline HR</h4><p class="text-gray-700">{{ $val($su->baseline_hr) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Baseline SpO₂</h4><p class="text-gray-700">{{ $val($su->baseline_spo2) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Baseline Temp (°C)</h4><p class="text-gray-700">{{ $val($su->baseline_temp) }}</p></div>

        <div><h4 class="text-sm text-gray-500">Fall Risk</h4><p class="text-gray-700">{{ $val($su->fall_risk) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Choking Risk</h4><p class="text-gray-700">{{ $val($su->choking_risk) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Pressure Ulcer Risk</h4><p class="text-gray-700">{{ $val($su->pressure_ulcer_risk) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Wander / Elopement Risk</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->wander_elopement_risk ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->wander_elopement_risk) }}</span></div>

        <div><h4 class="text-sm text-gray-500">Safeguarding Flag</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->safeguarding_flag ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->safeguarding_flag) }}</span></div>
        <div><h4 class="text-sm text-gray-500">Infection Control Flag</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->infection_control_flag ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->infection_control_flag) }}</span></div>
        <div><h4 class="text-sm text-gray-500">Smoking Status</h4><p class="text-gray-700">{{ $val($su->smoking_status) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Capacity Status</h4><p class="text-gray-700">{{ $val($su->capacity_status) }}</p></div>
      </div>
    </section>

    {{-- Legal & Consent --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Legal & Consent</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Consent Obtained At</h4><p class="text-gray-700">{{ $su->consent_obtained_at ? \Illuminate\Support\Carbon::parse($su->consent_obtained_at)->format('d M Y H:i') : '—' }}</p></div>
        <div><h4 class="text-sm text-gray-500">DNACPR Status</h4><p class="text-gray-700">{{ $val($su->dnacpr_status) }}</p></div>
        <div><h4 class="text-sm text-gray-500">DNACPR Review Date</h4><p class="text-gray-700">{{ $fmtDate($su->dnacpr_review_date) }}</p></div>
        <div><h4 class="text-sm text-gray-500">DoLS in Place</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->dols_in_place ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->dols_in_place) }}</span></div>
        <div><h4 class="text-sm text-gray-500">DoLS Approval Date</h4><p class="text-gray-700">{{ $fmtDate($su->dols_approval_date) }}</p></div>
        <div><h4 class="text-sm text-gray-500">LPA: Health & Welfare</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->lpa_health_welfare ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->lpa_health_welfare) }}</span></div>
        <div><h4 class="text-sm text-gray-500">LPA: Finance & Property</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->lpa_finance_property ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->lpa_finance_property) }}</span></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">Advanced Decision Note</h4><p class="text-gray-700 whitespace-pre-line">{{ $val($su->advanced_decision_note) }}</p></div>
      </div>
    </section>

    {{-- Preferences --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Preferences</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Ethnicity</h4><p class="text-gray-700">{{ $val($su->ethnicity) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Religion / Belief</h4><p class="text-gray-700">{{ $val($su->religion) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Primary Language</h4><p class="text-gray-700">{{ $val($su->primary_language) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Interpreter Required</h4><span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-white {{ $su->interpreter_required ? 'bg-green-600' : 'bg-gray-500' }}">{{ $yesNo($su->interpreter_required) }}</span></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">Personal Preferences</h4><p class="text-gray-700 whitespace-pre-line">{{ $val($su->personal_preferences) }}</p></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">Food Preferences</h4><p class="text-gray-700 whitespace-pre-line">{{ $val($su->food_preferences) }}</p></div>
      </div>
    </section>

    {{-- GP & Pharmacy --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">GP & Pharmacy</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">GP Practice Name</h4><p class="text-gray-700">{{ $val($su->gp_practice_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">GP Contact Name</h4><p class="text-gray-700">{{ $val($su->gp_contact_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">GP Phone</h4><p class="text-gray-700">{{ $val($su->gp_phone) }}</p></div>
        <div><h4 class="text-sm text-gray-500">GP Email</h4><p class="text-gray-700 break-all">{{ $val($su->gp_email) }}</p></div>
        <div class="md:col-span-2"><h4 class="text-sm text-gray-500">GP Address</h4><p class="text-gray-700 whitespace-pre-line">{{ $val($su->gp_address) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Pharmacy Name</h4><p class="text-gray-700">{{ $val($su->pharmacy_name) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Pharmacy Phone</h4><p class="text-gray-700">{{ $val($su->pharmacy_phone) }}</p></div>
      </div>
    </section>

    {{-- Audit --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Audit</h3>
      <div class="grid md:grid-cols-2 gap-4 mt-3">
        <div><h4 class="text-sm text-gray-500">Created By (ID)</h4><p class="text-gray-700">{{ $val($su->created_by) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Updated By (ID)</h4><p class="text-gray-700">{{ $val($su->updated_by) }}</p></div>
        <div><h4 class="text-sm text-gray-500">Created At</h4><p class="text-gray-700">{{ $su->created_at ? $su->created_at->format('d M Y H:i') : '—' }}</p></div>
        <div><h4 class="text-sm text-gray-500">Updated At</h4><p class="text-gray-700">{{ $su->updated_at ? $su->updated_at->format('d M Y H:i') : '—' }}</p></div>
        <div><h4 class="text-sm text-gray-500">Deleted At</h4><p class="text-gray-700">{{ $su->deleted_at ? $su->deleted_at->format('d M Y H:i') : '—' }}</p></div>
      </div>
    </section>

    {{-- Tags --}}
    <section>
      <h3 class="text-lg font-semibold border-b pb-1">Tags</h3>
      <div class="mt-3">
        @if (!empty($tags))
          <div class="flex flex-wrap gap-2">
            @foreach($tags as $tag)
              <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $tag }}</span>
            @endforeach
          </div>
        @else
          <p class="text-gray-700">—</p>
        @endif
      </div>
    </section>

  </div>

  {{-- Footer --}}
  <div class="mt-6 flex items-center justify-between print:hidden">
    <a href="{{ route('backend.admin.service-users.index') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
    <button onclick="window.print()" class="px-3 py-1 text-sm bg-gray-700 text-white rounded hover:bg-gray-900">🖨 Print</button>
  </div>

</div>

{{-- Print styles --}}
<style>
  @media print {
    @page { size: A4; margin: 12mm; }
    .print\:hidden { display: none !important; }
    .print\:border-0 { border: 0 !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:rounded-none { border-radius: 0 !important; }
    a[href]:after { content: ""; } /* avoid printing raw URLs */
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  }
</style>
@endsection
