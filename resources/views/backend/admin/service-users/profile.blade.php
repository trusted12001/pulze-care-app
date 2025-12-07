@extends('layouts.admin')

@section('title', 'Service User Profile')

@section('content')
    @php
        $fmtDate = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
        $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y, h:i A') : '—';

        $badge = match ($su->status) {
            'active' => 'bg-green-100 text-green-800 border border-green-200',
            'discharged' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'on_leave' => 'bg-blue-100 text-blue-800 border border-blue-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200',
        };
      @endphp

    <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- Header Section --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
                <div class="flex-1">
                    @php
                        $photoUrl = $su->passport_photo_url;
                        $initials = collect(explode(' ', $su->full_name))
                            ->filter()
                            ->map(fn($part) => mb_substr($part, 0, 1))
                            ->take(2)
                            ->implode('');
                    @endphp

                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-600 text-lg font-semibold overflow-hidden">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $su->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span>{{ $initials }}</span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 break-words">
                            {{ $su->full_name }}
                        </h1>
                    </div>

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
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <a href="{{ route('backend.admin.service-users.edit', $su->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                        <i class="ph ph-pencil-simple"></i>
                        <span>Edit Profile</span>
                    </a>

                    {{-- ✅ Open dedicated print view in a new tab --}}
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
            <div class="inline-flex items-center">
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

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 mb-6">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Status</div>
                <div>
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs sm:text-sm font-medium {{ $badge }}">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ $su->status === 'active' ? 'bg-green-500' : ($su->status === 'discharged' ? 'bg-amber-500' : 'bg-gray-500') }}"></span>
                        {{ ucfirst($su->status) }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Admission Date</div>
                <div class="text-sm sm:text-base font-medium text-gray-900">
                    {{ optional($su->admission_date)->format('d M Y') ?? '—' }}
                </div>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Location</div>
                <div class="text-sm sm:text-base font-medium text-gray-900">
                    @if($su->relationLoaded('location') && $su->location)
                        {{ $su->location->name }}
                        @if($su->location->city) <span class="text-gray-500">• {{ $su->location->city }}</span> @endif
                        @if($su->location->postcode) <span class="text-gray-500">• {{ $su->location->postcode }}</span> @endif
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs Nav --}}
        <nav class="mb-6 flex flex-wrap gap-2">
            <a href="#identity"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'identity' || !request()->has('section') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Identity & Inclusion
            </a>
            <a href="#communication"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'communication' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Communication
            </a>
            <a href="#clinical-flags"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'clinical-flags' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Clinical Flags
            </a>
            <a href="#baselines"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'baselines' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Baselines
            </a>
            <a href="#risks"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'risks' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Risks & Safeguarding
            </a>
            <a href="#legal-consent"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'legal-consent' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Legal & Consent
            </a>
            <a href="#preferences"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'preferences' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Preferences
            </a>
            <a href="#gp-pharmacy"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'gp-pharmacy' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                GP & Pharmacy
            </a>
            <a href="#tags"
                class="px-3 sm:px-4 py-2 text-sm rounded-lg transition-colors duration-200 {{ request()->get('section') === 'tags' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Tags
            </a>
        </nav>

        {{-- SECTION: Identity & Inclusion --}}
        <section id="identity" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-user text-blue-600"></i>
                        <span>Identity & Inclusion</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._identity', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Communication --}}
        <section id="communication" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-chat-circle text-purple-600"></i>
                        <span>Communication</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._communication', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Clinical Flags --}}
        <section id="clinical-flags" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-heartbeat text-emerald-600"></i>
                        <span>Clinical Flags & Plans</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._clinical_flags', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Baselines --}}
        <section id="baselines" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-chart-line text-amber-600"></i>
                        <span>Health Baselines</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._baselines', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Risks --}}
        <section id="risks" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-warning text-red-600"></i>
                        <span>Risks & Safeguarding</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._risks', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Legal & Consent --}}
        <section id="legal-consent" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-scroll text-indigo-600"></i>
                        <span>Legal & Consent</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._legal_consent', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Preferences --}}
        <section id="preferences" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-heart text-green-600"></i>
                        <span>Preferences</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._preferences', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: GP & Pharmacy --}}
        <section id="gp-pharmacy" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-stethoscope text-teal-600"></i>
                        <span>GP & Pharmacy</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._gp_pharmacy', ['su' => $su])
                </div>
            </div>
        </section>

        {{-- SECTION: Tags --}}
        <section id="tags" class="scroll-mt-24 mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div
                    class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-tag text-gray-600"></i>
                        <span>Tags</span>
                    </h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @include('backend.admin.service-users.tabs._tags', ['su' => $su])
                </div>
            </div>
        </section>

    </div>
@endsection