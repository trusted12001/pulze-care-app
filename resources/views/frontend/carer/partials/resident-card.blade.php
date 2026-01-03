@php
    /** @var \App\Models\ServiceUser $resident */

    $residentName = trim(($resident->full_name ?? '') ?: (
        trim(($resident->first_name ?? '') . ' ' . ($resident->last_name ?? ''))
    )) ?: ($resident->name ?? 'Resident');

    $photo = $resident->passport_photo_url
        ?? ($resident->photo_path ? asset('storage/' . $resident->photo_path) : null)
        ?? asset('assets/img/top-doctor-1.png');

    $roomLabel = $resident->room_label
        ?? ($resident->room_number ? ('Room ' . $resident->room_number) : null)
        ?? 'Room not set';

    $locationLabel = optional($resident->location)->name ?? null;

    $dob = $resident->date_of_birth ? \Carbon\Carbon::parse($resident->date_of_birth)->format('d/m/Y') : 'Unknown';

    $tags = $resident->tags ?? null;
    if (is_string($tags)) {
        $decoded = json_decode($tags, true);
        if (json_last_error() === JSON_ERROR_NONE)
            $tags = $decoded;
    }
    $tags = is_array($tags) ? $tags : [];

    $isActive = ($resident->status ?? 'active') === 'active';
@endphp

<a href="{{ route('frontend.residents.show', $resident) }}" class="text-decoration-none d-block">
    <div class="w-100 top-doctor-item p-2 p-sm-3 rounded-3 bg-white shadow-sm">

        {{-- Row 1: avatar + name + status (compact, no empty right column on mobile) --}}
        <div class="d-flex align-items-start gap-2">

            <div class="position-relative rounded-circle overflow-hidden flex-center"
                style="width: 44px; height: 44px; flex: 0 0 44px;">
                <img src="{{ $photo }}" alt="{{ $residentName }}" class="w-100 h-100" style="object-fit: cover;" />
                @if($isActive)
                    <span class="position-absolute bottom-0 end-0 rounded-circle border border-white"
                        style="width: 10px; height: 10px; background: #22c55e;"></span>
                @endif
            </div>

            <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <p class="fw-bold mb-0 text-truncate" style="font-size: 0.95rem;">
                        {{ $residentName }}
                    </p>

                    <span
                        class="badge {{ $isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                        style="font-size: 0.72rem;">
                        {{ ucfirst($resident->status ?? 'active') }}
                    </span>
                </div>

                {{-- Room + Location (single compact line) --}}
                <p class="mb-0 small text-muted text-truncate" style="line-height: 1.2;">
                    <span class="me-1">{{ $roomLabel }}</span>
                    @if($locationLabel)
                        <i class="ph ph-dot fs-5 align-middle mx-1"></i>
                        <span>{{ $locationLabel }}</span>
                    @endif
                </p>

                {{-- DOB (compact) --}}
                <div class="d-flex align-items-center gap-2 small text-muted pt-1" style="line-height: 1.2;">
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="ph-fill ph-calendar-blank"></i>
                        <span>DOB:</span>
                    </span>
                    <span class="fw-semibold">{{ $dob }}</span>
                </div>

                {{-- Tags (compact chips) --}}
                @if(!empty($tags))
                    <div class="pt-2 d-flex flex-wrap gap-1">
                        @foreach(array_slice($tags, 0, 3) as $tag)
                            <span class="badge bg-light text-dark border" style="font-size: 0.72rem; font-weight: 500;">
                                {{ $tag }}
                            </span>
                        @endforeach
                        @if(count($tags) > 3)
                            <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                +{{ count($tags) - 3 }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Row 2: action + ID (compact) --}}
        <div class="d-flex align-items-center justify-content-between pt-2">
            <span class="small fw-semibold p1-color">
                Open profile &amp; plan
            </span>

            <span class="small text-muted fw-semibold">
                ID: {{ $resident->id }}
            </span>
        </div>

    </div>
</a>