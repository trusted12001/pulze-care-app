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

<a href="{{ route('frontend.residents.show', $resident) }}" class="text-decoration-none d-block resident-card-link">
    <div class="w-100 top-doctor-item p-3 rounded-4 bg-white shadow-sm border-0"
        style="transition: all 0.2s ease; border: 1.5px solid transparent;">

        {{-- Row 1: avatar + name + status (compact, no empty right column on mobile) --}}
        <div class="d-flex align-items-start gap-2">

            <div class="position-relative rounded-circle overflow-hidden flex-center flex-shrink-0"
                style="width: 56px; height: 56px; flex: 0 0 56px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                <img src="{{ $photo }}" alt="{{ $residentName }}" class="w-100 h-100" style="object-fit: cover;" />
                @if($isActive)
                    <span class="position-absolute bottom-0 end-0 rounded-circle border border-white"
                        style="width: 14px; height: 14px; background: #22c55e; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"></span>
                @endif
            </div>

            <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    <h5 class="fw-bold mb-0 text-truncate" style="font-size: 1rem; color: var(--n1); line-height: 1.3;">
                        {{ $residentName }}
                    </h5>

                    <span
                        class="badge {{ $isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-2 py-1 flex-shrink-0"
                        style="font-size: 0.75rem; font-weight: 500;">
                        {{ ucfirst($resident->status ?? 'active') }}
                    </span>
                </div>

                {{-- Room + Location (single compact line) --}}
                <p class="mb-1 small text-muted d-flex align-items-center gap-1" style="line-height: 1.3; font-size: 0.8125rem;">
                    <i class="ph-fill ph-map-pin" style="font-size: 0.875rem;"></i>
                    <span class="text-truncate">{{ $roomLabel }}</span>
                    @if($locationLabel)
                        <span class="mx-1">•</span>
                        <span class="text-truncate">{{ $locationLabel }}</span>
                    @endif
                </p>

                {{-- DOB (compact) --}}
                <div class="d-flex align-items-center gap-1 small text-muted" style="line-height: 1.3; font-size: 0.8125rem;">
                    <i class="ph-fill ph-calendar-blank" style="font-size: 0.875rem;"></i>
                    <span>DOB: <span class="fw-medium">{{ $dob }}</span></span>
                </div>

                {{-- Tags (compact chips) --}}
                @if(!empty($tags))
                    <div class="pt-2 d-flex flex-wrap gap-1">
                        @foreach(array_slice($tags, 0, 3) as $tag)
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem; font-weight: 500;">
                                {{ $tag }}
                            </span>
                        @endforeach
                        @if(count($tags) > 3)
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">
                                +{{ count($tags) - 3 }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Row 2: action + ID (compact) --}}
        <div class="d-flex align-items-center justify-content-between pt-2 mt-2 border-top"
            style="border-color: var(--borderColor) !important; padding-top: 0.75rem !important;">
            <span class="small fw-semibold d-flex align-items-center gap-1"
                style="color: var(--p1); font-size: 0.875rem;">
                <i class="ph ph-arrow-right" style="font-size: 0.875rem;"></i>
                <span>View profile</span>
            </span>

            <span class="small text-muted fw-medium" style="font-size: 0.75rem;">
                ID: {{ $resident->id }}
            </span>
        </div>

    </div>
</a>