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

    // Tags could be array or JSON string depending on your schema/cast
    $tags = $resident->tags ?? null;
    if (is_string($tags)) {
        $decoded = json_decode($tags, true);
        if (json_last_error() === JSON_ERROR_NONE)
            $tags = $decoded;
    }
    $tags = is_array($tags) ? $tags : [];
@endphp

<a href="{{ route('frontend.residents.show', $resident) }}" class="text-decoration-none">
    <div class="w-100 top-doctor-item p-3 rounded-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div class="d-flex justify-content-start align-items-start gap-3">
                <div class="doctor-img flex-center rounded-circle overflow-hidden" style="width: 56px; height: 56px;">
                    <img src="{{ $photo }}" class="h-100 w-100" style="object-fit: cover;" alt="{{ $residentName }}" />
                    {{-- optional “active” indicator --}}
                    @if(($resident->status ?? 'active') === 'active')
                        <img src="{{ asset('assets/img/active.png') }}" class="active" alt="Active" />
                    @endif
                </div>

                <div class="flex-grow-1">
                    <p class="fw-bold name mb-1">{{ $residentName }}</p>

                    <p
                        class="d-inline-flex justify-content-start align-items-center py-1 flex-wrap mb-1 small text-muted">
                        <span class="category me-1">{{ $roomLabel }}</span>
                        @if($locationLabel)
                            <i class="ph ph-dot fs-5 mx-1"></i>
                            <span class="work-place">{{ $locationLabel }}</span>
                        @endif
                    </p>

                    <div class="d-flex justify-content-start align-items-center flex-wrap small text-muted">
                        <div class="rating d-flex align-items-center me-2">
                            <i class="ph-fill ph-star me-1"></i> DOB
                        </div>
                        <i class="ph ph-dot fs-5 me-2"></i>
                        <div class="time d-flex align-items-center">
                            <i class="ph-fill ph-clock me-1"></i>
                            {{ $dob }}
                        </div>
                    </div>

                    @if(!empty($tags))
                        <div class="pt-2 d-flex flex-wrap gap-2">
                            @foreach(array_slice($tags, 0, 3) as $tag)
                                <span class="badge bg-light text-dark border">{{ $tag }}</span>
                            @endforeach
                            @if(count($tags) > 3)
                                <span class="badge bg-light text-dark border">+{{ count($tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-end">
                <span
                    class="badge {{ ($resident->status ?? 'active') === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                    {{ ucfirst($resident->status ?? 'active') }}
                </span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3">
            <span class="appointment-link d-block p1-color small fw-semibold">
                Open profile &amp; plan
            </span>
            <div class="custom-border-area position-relative mx-3 flex-grow-1"></div>
            <p class="fs-6 fw-bold mb-0">ID: {{ $resident->id }}</p>
        </div>
    </div>
</a>