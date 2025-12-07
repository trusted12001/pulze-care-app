@extends('layouts.admin')

@section('title', 'Service User Profile')

@section('content')
    @php
        // Safe helpers for display
        $val = fn($v) => (isset($v) && $v !== '' && $v !== null ? $v : '—');
        $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
        $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y, h:i A') : '—';
        $fmtMoney = fn($n) => is_null($n) ? '—' : '£' . number_format($n, 2);
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
        $fullName = $su->full_name;

        $badge = match ($su->status) {
            'active' => 'bg-green-100 text-green-800 border border-green-200',
            'discharged' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'on_leave' => 'bg-blue-100 text-blue-800 border border-blue-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200',
        };

        // Build full address
        $addressParts = array_filter([
            $su->address_line1,
            $su->address_line2,
            $su->city,
            $su->county,
            $su->postcode,
            $su->country
        ]);
        $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : '—';
      @endphp

    <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8 print:py-0">

        {{-- Header Section --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 break-words">
                        {{ $fullName }}
                    </h1>
                    <div
                        class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                        @if($su->date_of_birth)
                            <span class="flex items-center gap-1.5">
                                <i class="ph ph-calendar flex-shrink-0"></i>
                                <span class="whitespace-nowrap">DOB: {{ $fmtDate($su->date_of_birth) }}</span>
                            </span>
                            <span class="hidden sm:inline text-gray-300">•</span>
                        @endif
                        @if($su->postcode)
                            <span class="flex items-center gap-1.5">
                                <i class="ph ph-map-pin flex-shrink-0"></i>
                                Postcode: {{ $su->postcode }}
                            </span>
                            <span class="hidden sm:inline text-gray-300">•</span>
                        @endif
                        @if($su->location)
                            <span class="flex items-center gap-1.5">
                                <i class="ph ph-buildings flex-shrink-0"></i>
                                {{ $su->location->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto print:hidden">
                    <a href="{{ route('backend.admin.service-users.edit', $su->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                        <i class="ph ph-pencil-simple"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="{{ route('backend.admin.service-users.profile', $su->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 active:bg-purple-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                        <i class="ph ph-user-circle"></i>
                        <span>Full Profile</span>
                    </a>
                    <a href="{{ route('backend.admin.service-users.print', $su->id) }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                        <i class="ph ph-printer"></i>
                        <span>Print / PDF</span>
                    </a>

                    <a href="{{ route('backend.admin.service-users.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                        <i class="ph ph-arrow-left"></i>
                        <span>Back to List</span>
                    </a>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="inline-flex items-center print:hidden">
                <span
                    class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm {{ $badge }}">
                    <span
                        class="w-2 h-2 rounded-full {{ $su->status === 'active' ? 'bg-green-500' : ($su->status === 'discharged' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
                    {{ ucfirst($su->status) }}
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

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">

            {{-- Left Column - Main Info --}}
            <div class="lg:col-span-2 space-y-4 sm:space-y-5 lg:space-y-6">

                {{-- Personal Information Card --}}
                <div
                    class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
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
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">First
                                    Name</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->first_name) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Middle
                                    Name</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->middle_name) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Last
                                    Name</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->last_name) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Preferred
                                    Name</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->preferred_name) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Date
                                    of
                                    Birth</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtDate($su->date_of_birth) }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Sex
                                    at
                                    Birth</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->sex_at_birth) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Gender
                                    Identity</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->gender_identity) }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Pronouns</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->pronouns) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">NHS
                                    Number</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->nhs_number) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">National
                                    Insurance No</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">
                                    {{ $val($su->national_insurance_no) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact & Address Card --}}
                <div
                    class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                    <div
                        class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-phone text-purple-600 flex-shrink-0"></i>
                            <span>Contact & Address</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 lg:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Primary
                                    Phone</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->primary_phone) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Secondary
                                    Phone</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->secondary_phone) }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Email</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-all">{{ $val($su->email) }}
                                </p>
                            </div>
                            @if($fullAddress !== '—')
                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Full
                                        Address</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $fullAddress }}</p>
                                </div>
                            @endif
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Address
                                    Line 1</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                    {{ $val($su->address_line1) }}</p>
                            </div>
                            @if($su->address_line2)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Address
                                        Line 2</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                        {{ $val($su->address_line2) }}</p>
                                </div>
                            @endif
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">City</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->city) }}</p>
                            </div>
                            @if($su->county)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">County</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->county) }}</p>
                                </div>
                            @endif
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Postcode</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->postcode) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Country</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->country) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Placement & Status Card --}}
                <div
                    class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                    <div
                        class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-600 flex-shrink-0"></i>
                            <span>Placement & Status</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 lg:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Placement
                                    Type</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->placement_type) }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Location</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">
                                    {{ optional($su->location)->name ?? '—' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Room
                                    Number</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->room_number) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $su->status === 'active' ? 'bg-green-500' : ($su->status === 'discharged' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
                                    {{ ucfirst($su->status) }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Admission
                                    Date</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">
                                    {{ $fmtDate($su->admission_date) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Expected
                                    Discharge</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">
                                    {{ $fmtDate($su->expected_discharge_date) }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Discharge
                                    Date</label>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">
                                    {{ $fmtDate($su->discharge_date) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Funding Card --}}
                @if($su->funding_type || $su->funding_authority || $su->weekly_rate)
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-currency-pound text-amber-600 flex-shrink-0"></i>
                                <span>Funding</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Funding
                                        Type</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->funding_type) }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Funding
                                        Authority</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->funding_authority) }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Purchase
                                        Order Ref</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">
                                        {{ $val($su->purchase_order_ref) }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Weekly
                                        Rate</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $fmtMoney($su->weekly_rate) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Health & Clinical Card --}}
                @if($su->primary_diagnosis || $su->allergies_summary || $su->diet_type || $su->mobility_status)
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-heartbeat text-red-600 flex-shrink-0"></i>
                                <span>Health & Clinical</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Primary
                                        Diagnosis</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                        {{ $val($su->primary_diagnosis) }}
                                    </p>
                                </div>
                                @if($su->other_diagnoses)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Other
                                            Diagnoses</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                            {{ $val($su->other_diagnoses) }}</p>
                                    </div>
                                @endif
                                @if($su->allergies_summary)
                                    <div class="sm:col-span-2">
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Allergies
                                            Summary</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium whitespace-pre-line">
                                            {{ $val($su->allergies_summary) }}
                                        </p>
                                    </div>
                                @endif
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Diet
                                        Type</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->diet_type) }}</p>
                                </div>
                                @if($su->intolerances)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Intolerances</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                            {{ $val($su->intolerances) }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Mobility
                                        Status</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->mobility_status) }}
                                    </p>
                                </div>
                                @if($su->communication_needs)
                                    <div class="sm:col-span-2">
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Communication
                                            Needs</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium whitespace-pre-line">
                                            {{ $val($su->communication_needs) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Care Plans & Baselines Card --}}
                @if($su->behaviour_support_plan || $su->seizure_care_plan || $su->diabetes_care_plan || $su->baseline_bp || $su->baseline_hr)
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-clipboard-text text-indigo-600 flex-shrink-0"></i>
                                <span>Care Plans & Baselines</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Behaviour
                                        Support Plan</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->behaviour_support_plan ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->behaviour_support_plan ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                                        {{ $yesNo($su->behaviour_support_plan) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Seizure
                                        Care Plan</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->seizure_care_plan ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->seizure_care_plan ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                                        {{ $yesNo($su->seizure_care_plan) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Diabetes
                                        Care Plan</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->diabetes_care_plan ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->diabetes_care_plan ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                                        {{ $yesNo($su->diabetes_care_plan) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Oxygen
                                        Therapy</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->oxygen_therapy ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->oxygen_therapy ? 'ph-check-circle' : 'ph-x-circle' }}"></i>
                                        {{ $yesNo($su->oxygen_therapy) }}
                                    </span>
                                </div>
                                @if($su->baseline_bp || $su->baseline_hr || $su->baseline_spo2 || $su->baseline_temp)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Baseline
                                            BP</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->baseline_bp) }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Baseline
                                            HR</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->baseline_hr) }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Baseline
                                            SpO₂</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->baseline_spo2) }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Baseline
                                            Temp (°C)</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->baseline_temp) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Risks & Safeguarding Card --}}
                @if($su->fall_risk || $su->choking_risk || $su->pressure_ulcer_risk || $su->wander_elopement_risk || $su->safeguarding_flag)
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-warning text-red-600 flex-shrink-0"></i>
                                <span>Risks & Safeguarding</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Fall
                                        Risk</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->fall_risk) }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Choking
                                        Risk</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->choking_risk) }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Pressure
                                        Ulcer Risk</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">
                                        {{ $val($su->pressure_ulcer_risk) }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Wander
                                        /
                                        Elopement Risk</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->wander_elopement_risk ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->wander_elopement_risk ? 'ph-warning' : 'ph-check-circle' }}"></i>
                                        {{ $yesNo($su->wander_elopement_risk) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Safeguarding
                                        Flag</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->safeguarding_flag ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->safeguarding_flag ? 'ph-warning' : 'ph-check-circle' }}"></i>
                                        {{ $yesNo($su->safeguarding_flag) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Infection
                                        Control Flag</label>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $su->infection_control_flag ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="ph {{ $su->infection_control_flag ? 'ph-warning' : 'ph-check-circle' }}"></i>
                                        {{ $yesNo($su->infection_control_flag) }}
                                    </span>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Smoking
                                        Status</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->smoking_status) }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Capacity
                                        Status</label>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->capacity_status) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- GP & Pharmacy Card --}}
                @if($su->gp_practice_name || $su->gp_phone || $su->pharmacy_name)
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-stethoscope text-teal-600 flex-shrink-0"></i>
                                <span>GP & Pharmacy</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                @if($su->gp_practice_name)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GP
                                            Practice
                                            Name</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                            {{ $val($su->gp_practice_name) }}
                                        </p>
                                    </div>
                                @endif
                                @if($su->gp_contact_name)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GP
                                            Contact
                                            Name</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                            {{ $val($su->gp_contact_name) }}</p>
                                    </div>
                                @endif
                                @if($su->gp_phone)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GP
                                            Phone</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->gp_phone) }}</p>
                                    </div>
                                @endif
                                @if($su->gp_email)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GP
                                            Email</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-all">
                                            {{ $val($su->gp_email) }}</p>
                                    </div>
                                @endif
                                @if($su->gp_address)
                                    <div class="sm:col-span-2">
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">GP
                                            Address</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium whitespace-pre-line">
                                            {{ $val($su->gp_address) }}
                                        </p>
                                    </div>
                                @endif
                                @if($su->pharmacy_name)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Pharmacy
                                            Name</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium break-words">
                                            {{ $val($su->pharmacy_name) }}</p>
                                    </div>
                                @endif
                                @if($su->pharmacy_phone)
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Pharmacy
                                            Phone</label>
                                        <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $val($su->pharmacy_phone) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tags Card --}}
                @if(!empty($tags))
                    <div
                        class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-0 print:shadow-none print:rounded-none">
                        <div
                            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ph ph-tag text-gray-600 flex-shrink-0"></i>
                                <span>Tags</span>
                            </h2>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <i class="ph ph-tag"></i>
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column - Quick Stats & Actions --}}
            {{-- Right Column - Photo, Quick Stats & Actions --}}
            <div class="space-y-4 sm:space-y-5 lg:space-y-6">

                {{-- Passport Photo Card --}}
                <div
                    class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:hidden">
                    <div
                        class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-200">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-identification-card text-sky-600 flex-shrink-0"></i>
                            <span>Passport Photo</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 lg:p-6 flex flex-col items-center gap-3">

                        @php
                            $photoUrl = $su->passport_photo_url;
                            $initials = collect(explode(' ', $su->full_name))
                                ->filter()
                                ->map(fn($part) => mb_substr($part, 0, 1))
                                ->take(2)
                                ->implode('');
                          @endphp

                        <div
                            class="relative inline-flex items-center justify-center w-28 h-28 rounded-full bg-gray-100 text-gray-600 text-2xl font-semibold overflow-hidden">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $su->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span>{{ $initials }}</span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 text-center">
                            This photo will be used on reports and key screens for this service user.
                        </p>

                        <a href="{{ route('backend.admin.service-users.photo.edit', $su->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <i class="ph ph-upload-simple"></i>
                            <span>{{ $photoUrl ? 'Change Photo' : 'Upload Photo' }}</span>
                        </a>
                    </div>
                </div>

                {{-- Quick Stats Card --}}
                <div
                    class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden print:hidden">
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
                                    {{ $fmtDate($su->created_at) }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Created</p>
                            </div>
                            @if($su->admission_date)
                                <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                                    <p class="text-xl sm:text-2xl font-bold text-gray-900 break-words">
                                        {{ $fmtDate($su->admission_date) }}</p>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Admission Date</p>
                                </div>
                            @endif
                            @if($su->date_of_birth)
                                @php
                                    $age = $su->date_of_birth->age ?? null;
                                  @endphp
                                @if($age !== null)
                                    <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $age }}</p>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Years Old</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Card --}}
                @* keep your existing Quick Actions + System Information blocks unchanged *@
                {{-- (copy your existing Quick Actions and System Information blocks here) --}}
                {!! '' !!}
            </div>

        </div>

    </div>

    {{-- Print Styles --}}
    <style>
        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:border-0 {
                border: 0 !important;
            }

            .print\:shadow-none {
                box-shadow: none !important;
            }

            .print\:rounded-none {
                border-radius: 0 !important;
            }

            .print\:py-0 {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            a[href]:after {
                content: "";
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Ensure cards print properly */
            .bg-white {
                background: white !important;
            }

            /* Hide gradients in print */
            .bg-gradient-to-r {
                background: #f9fafb !important;
            }
        }
    </style>
@endsection
