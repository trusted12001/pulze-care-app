@extends('layouts.admin')

@section('title', $assignment->title)

@section('content')
    @php
        $fmtDT = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y · H:i') : '—';
    @endphp

    <div class="min-h-screen p-3 sm:p-4 lg:p-6">
        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        {{ ucfirst($assignment->type) }} • {{ ucfirst($assignment->priority) }} priority
                        <span class="text-gray-400">•</span>
                        Status:
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                            @class([
                                                'bg-yellow-100 text-yellow-800' => in_array($assignment->status, ['scheduled', 'overdue']),
                                                'bg-blue-100 text-blue-800' => $assignment->status === 'in_progress',
                                                'bg-purple-100 text-purple-800' => $assignment->status === 'submitted',
                                                'bg-green-100 text-green-800' => in_array($assignment->status, ['verified', 'closed']),
                                                'bg-gray-100 text-gray-800' => in_array($assignment->status, ['draft', 'expired', 'declined']),
                                            ])">
                            <span class="w-1.5 h-1.5 rounded-full
                                                @class([
                                                    'bg-yellow-500' => in_array($assignment->status, ['scheduled', 'overdue']),
                                                    'bg-blue-500' => $assignment->status === 'in_progress',
                                                    'bg-purple-500' => $assignment->status === 'submitted',
                                                    'bg-green-500' => in_array($assignment->status, ['verified', 'closed']),
                                                    'bg-gray-500' => in_array($assignment->status, ['draft', 'expired', 'declined']),
                                                ])"></span>
                            {{ str_replace('_', ' ', ucfirst($assignment->status)) }}
                        </span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('backend.admin.assignments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="ph ph-arrow-left"></i><span>Back</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Meta / Context --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="ph ph-info text-blue-600"></i> Details
                </h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $assignment->description }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 text-sm">
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-map-pin text-gray-500"></i>
                        <span class="font-medium">Location:</span>
                        <span>{{ optional($assignment->location)->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-user text-gray-500"></i>
                        <span class="font-medium">Service User:</span>
                        <span>
                            {{ optional($assignment->resident)->full_name
        ?? (optional($assignment->resident)->first_name
            ? optional($assignment->resident)->first_name . ' ' . optional($assignment->resident)->last_name
            : '—') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-calendar text-gray-500"></i>
                        <span class="font-medium">Window:</span>
                        <span>{{ $fmtDT($assignment->window_start) }} → {{ $fmtDT($assignment->window_end) }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-hourglass text-gray-500"></i>
                        <span class="font-medium">Due:</span>
                        <span>{{ $fmtDT($assignment->due_at) }}</span>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @if($assignment->requires_gps)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                            <i class="ph ph-crosshair-simple"></i> GPS required
                        </span>
                    @endif
                    @if($assignment->requires_photo)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                            <i class="ph ph-camera"></i> Photo evidence
                        </span>
                    @endif
                    @if($assignment->requires_signature)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            <i class="ph ph-pencil-circle"></i> Signature
                        </span>
                    @endif
                </div>



                {{-- 🔍 Photo Evidence Thumbnails (Admin / Super Admin, submitted or verified) --}}
                @php
                    $user = auth()->user();
                    $canSeeEvidenceThumbs = $user
                        && $user->hasAnyRole(['Super Admin', 'admin'])
                        && in_array($assignment->status, ['submitted', 'verified'])
                        && ($assignment->evidence ?? false)
                        && $assignment->evidence->count() > 0;
                @endphp

                @if($canSeeEvidenceThumbs)
                    <div class="mt-5 border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="ph ph-images text-amber-600"></i>
                            <span>Photo Evidence</span>
                        </h4>

                        <div class="flex flex-wrap gap-3">
                            @foreach($assignment->evidence as $ev)
                                @if($ev->file_type && str_starts_with($ev->file_type, 'image/'))
                                    <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank"
                                        class="block w-20 h-20 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 hover:ring-2 hover:ring-blue-500 transition">
                                        <img src="{{ asset('storage/' . $ev->file_path) }}" alt="Evidence photo"
                                            class="w-full h-full object-cover">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- 🔚 End thumbnails block --}}
            </div>

            {{-- Preflight Panel --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="ph ph-rocket-launch text-emerald-600"></i> Preflight Check
                </h3>
                <div class="space-y-2 text-sm">
                    <div id="gpsStatus" class="flex items-start gap-2 text-gray-700">
                        <i class="ph ph-crosshair-simple mt-0.5 text-gray-500"></i>
                        <div>
                            <div class="font-medium">GPS</div>
                            <div id="gpsMsg" class="text-gray-600">Checking location…</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-gray-700">
                        <i class="ph ph-clock-afternoon mt-0.5 text-gray-500"></i>
                        <div>
                            <div class="font-medium">Window</div>
                            <div class="text-gray-600">
                                {{ $assignment->window_start && $assignment->window_end ? 'This task must start within the specified window.' : 'No strict window set.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    {{-- If YOU are the assigned staff, show the Start button --}}
                    @if(auth()->id() === $assignment->assigned_to)

                        @if($assignment->status === 'scheduled')
                            <button type="button" onclick="startTask()"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="ph ph-play"></i> Start
                            </button>
                        @elseif($assignment->status === 'in_progress')
                            <div class="inline-flex items-center gap-2 text-blue-700 bg-blue-50 px-3 py-2 rounded">
                                <i class="ph ph-timer"></i> In progress
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 text-gray-700 bg-gray-100 px-3 py-2 rounded">
                                <i class="ph ph-info"></i> {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                            </div>
                        @endif

                    @else
                        {{-- NOT the assignee → NEVER show the Start button --}}
                        @if($assignment->status === 'scheduled')
                            <div class="inline-flex items-center gap-2 text-gray-700 bg-gray-100 px-3 py-2 rounded">
                                <i class="ph ph-clock"></i> Scheduled
                            </div>
                        @elseif($assignment->status === 'in_progress')
                            <div class="inline-flex items-center gap-2 text-blue-700 bg-blue-50 px-3 py-2 rounded">
                                <i class="ph ph-timer"></i> In progress
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 text-gray-700 bg-gray-100 px-3 py-2 rounded">
                                <i class="ph ph-info"></i> {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                            </div>
                        @endif

                    @endif

                </div>

                {{-- Hidden inputs populated by geolocation --}}
                <input type="hidden" id="lat" value="">
                <input type="hidden" id="lng" value="">
                <input type="hidden" id="accuracy" value="">
            </div>
        </div>

        {{-- Complete & Submit --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-50 to-fuchsia-50 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="ph ph-paper-plane-right text-purple-600"></i>
                    <span>Complete & Submit</span>
                </h3>
            </div>

            <form id="submitForm" class="p-4 sm:p-6" onsubmit="return false;" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="note" rows="4"
                                class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500"
                                placeholder="Observations, steps taken, outcomes…"></textarea>
                        </div>

                        @if($assignment->requires_signature)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Signature</label>
                                <input type="text" name="signature_initials"
                                    placeholder="Enter initials as sign-off (temp placeholder)"
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                <p class="text-xs text-gray-500 mt-1">
                                    We’ll replace this with a canvas signature pad in Batch 4 if you like.
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @if($assignment->requires_photo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Photo Evidence</label>
                                <input type="file" name="photos[]" accept="image/*,.pdf" multiple
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                <p class="text-xs text-gray-500 mt-1">Images or PDF. Max 10MB per file.</p>
                            </div>
                        @endif

                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                <i class="ph ph-navigation-arrow"></i> Location Meta
                            </h4>
                            <p class="text-xs text-gray-600">
                                Latitude: <span id="metaLat">—</span> • Longitude: <span id="metaLng">—</span> • Accuracy:
                                <span id="metaAcc">—</span>
                            </p>
                        </div>
                    </div>
                </div>
            </form> {{-- 🔚 END submitForm (used only for Submit for Verification via JS) --}}

            {{-- 🔹 Submit for Verification (only when NOT submitted / verified) --}}
            @if(!in_array($assignment->status, ['submitted', 'verified']))
                <div class="mt-6 flex items-center justify-end gap-3 mr-4">
                    <a href="{{ route('backend.admin.assignments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="ph ph-x"></i> Cancel
                    </a>
                    <button type="button" onclick="submitTask()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                        @disabled(!in_array($assignment->status, ['in_progress', 'scheduled']))>
                        <i class="ph ph-paper-plane-right"></i> Submit for Verification
                    </button>
                </div>
            @endif

            {{-- 🔹 Verification block OUTSIDE submitForm to avoid nested forms --}}
            <div class="mt-6 bg-gray-50 rounded-lg p-3 border border-gray-200">
                @can('verify', $assignment)
                    @if($assignment->status === 'submitted')
                        <div class="mt-2 border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="ph ph-shield-check text-emerald-600"></i>
                                <span>Verification</span>
                            </h3>

                            <form method="POST" action="{{ route('backend.admin.assignments.verify.post', $assignment) }}"
                                class="space-y-3 sm:space-y-4 max-w-xl">
                                @csrf

                                <div>
                                    <label for="verify_comment" class="block text-xs font-medium text-gray-700 mb-1">
                                        Verification comment (optional)
                                    </label>
                                    <textarea id="verify_comment" name="comment" rows="2"
                                        class="mb-3 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm
                                                                                         focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                                                         @error('comment') border-red-500 ring-1 ring-red-300 @enderror"
                                        placeholder="e.g. Checked notes and evidence – task completed as expected.">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <a href="{{ route('backend.admin.assignments.index') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    <i class="ph ph-x"></i> Cancel
                                </a>

                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium
                                                                                   bg-emerald-600 text-white hover:bg-emerald-700 active:bg-emerald-800
                                                                                   shadow-sm hover:shadow-md transition-colors duration-150"
                                    onclick="return confirm('Mark this assignment as verified and closed?');">
                                    <i class="ph ph-check-circle"></i>
                                    <span>Verify & Close</span>
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>

        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Basic geolocation helper
        function getGeo(cb) {
            if (!navigator.geolocation) return cb(null);
            navigator.geolocation.getCurrentPosition(
                pos => cb({ lat: pos.coords.latitude, lng: pos.coords.longitude, acc: pos.coords.accuracy }),
                _ => cb(null),
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 10000 }
            );
        }

        // Populate GPS
        getGeo(p => {
            const gpsMsg = document.getElementById('gpsMsg');
            if (p) {
                document.getElementById('lat').value = p.lat;
                document.getElementById('lng').value = p.lng;
                document.getElementById('accuracy').value = p.acc;
                document.getElementById('metaLat').textContent = p.lat.toFixed(6);
                document.getElementById('metaLng').textContent = p.lng.toFixed(6);
                document.getElementById('metaAcc').textContent = `${Math.round(p.acc)}m`;
                gpsMsg.textContent = `GPS OK (± ${Math.round(p.acc)}m)`;
                document.getElementById('gpsStatus').classList.remove('text-red-700');
            } else {
                gpsMsg.textContent = 'GPS unavailable. If required, manager override will be needed.';
                document.getElementById('gpsStatus').classList.add('text-red-700');
            }
        });

        async function startTask() {
            const lat = document.getElementById('lat').value || '';
            const lng = document.getElementById('lng').value || '';
            const acc = document.getElementById('accuracy').value || '';

            if (REQUIRES_GPS && (!lat || !lng)) {
                alert(
                    'This assignment requires GPS, but we could not get your location.\n\n' +
                    'Please enable location permissions for this site in your browser (site settings → Location → Allow) and try again.'
                );
                return;
            }

            const fd = new FormData();
            fd.append('lat', lat);
            fd.append('lng', lng);
            fd.append('accuracy', acc);

            const res = await fetch('/api/assignments/{{ $assignment->id }}/start', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: fd
            });

            if (res.ok) {
                location.reload();
                return;
            }

            let text = await res.text();
            console.error('Start error response:', text);

            try {
                const data = JSON.parse(text);
                alert(data.message || 'Failed to start.');
            } catch (_) {
                alert('Failed to start.\n\n' + text.substring(0, 300));
            }
        }

        async function submitTask() {
            const form = document.getElementById('submitForm');
            const fd = new FormData(form);

            fd.append('lat', document.getElementById('lat').value || '');
            fd.append('lng', document.getElementById('lng').value || '');
            fd.append('accuracy', document.getElementById('accuracy').value || '');

            const res = await fetch('/api/assignments/{{ $assignment->id }}/submit', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });

            if (res.ok) {
                window.location.href = '{{ route('backend.admin.assignments.index') }}';
            } else {
                try {
                    const data = await res.json();
                    alert(data.message || 'Submit failed.');
                } catch (_) { alert('Submit failed.'); }
            }
        }
    </script>

    <script>
        const REQUIRES_GPS = {{ $assignment->requires_gps ? 'true' : 'false' }};
    </script>
@endsection