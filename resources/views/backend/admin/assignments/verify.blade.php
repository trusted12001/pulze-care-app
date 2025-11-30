@extends('layouts.admin')

@section('title', 'Verify: ' . $assignment->title)

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Str;
        $fmtDT = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y · H:i') : '—';
    @endphp

    <div class="min-h-screen p-3 sm:p-4 lg:p-6">
        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                        Verify: {{ $assignment->title }}
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        Submitted {{ $assignment->updated_at?->diffForHumans() ?? '—' }} •
                        Assignee: <span class="font-medium">{{ optional($assignment->assignee)->name ?? '—' }}</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Left: Evidence + Comment --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-gray-200">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-folder text-emerald-600"></i> Submitted Evidence
                        </h3>
                    </div>

                    <div class="p-4 sm:p-6">
                        @if($assignment->evidence->isEmpty())
                            <p class="text-sm text-gray-500">No files were provided for this submission.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($assignment->evidence as $e)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                        <div class="p-3">
                                            <p class="text-sm text-gray-700">{{ $e->note ?: '—' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Captured: {{ $fmtDT($e->captured_at) }} • GPS:
                                                {{ $e->lat ? number_format($e->lat, 6) : '—' }},
                                                {{ $e->lng ? number_format($e->lng, 6) : '—' }}
                                                @if($e->accuracy) (±{{ (int) $e->accuracy }}m) @endif
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 p-3 border-t border-gray-200">
                                            @php
                                                $isImg = Str::of($e->file_type)->contains(['image/', 'jpeg', 'jpg', 'png', 'webp', 'heic']);
                                                $url = Storage::disk('public')->url($e->file_path);
                                            @endphp
                                            @if($isImg)
                                                <a href="{{ $url }}" target="_blank" class="block">
                                                    <img src="{{ $url }}" alt="evidence" class="w-full h-40 object-cover rounded-md" />
                                                </a>
                                            @else
                                                <a href="{{ $url }}" target="_blank"
                                                    class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition text-sm">
                                                    <i class="ph ph-file"></i> Open File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-50 to-fuchsia-50 border-b border-gray-200">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-seal-check text-purple-600"></i> Verification Decision
                        </h3>
                    </div>

                    <form id="verifyForm" class="p-4 sm:p-6" onsubmit="return false;">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700">Comment (optional)</label>
                        <textarea name="comment" rows="3"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500"
                            placeholder="Notes about your verification decision…"></textarea>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('backend.admin.assignments.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                <i class="ph ph-x"></i> Cancel
                            </a>
                            <button type="button" onclick="verifyAndClose()"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                <i class="ph ph-check-circle"></i> Verify & Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right: Meta + Timeline --}}
            <aside class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                        <i class="ph ph-info text-blue-600"></i> Assignment Meta
                    </h3>
                    <dl class="text-sm text-gray-700 space-y-2">
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Status</dt>
                            <dd>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                        @class([
                            'bg-purple-100 text-purple-800' => $assignment->status === 'submitted',
                            'bg-blue-100 text-blue-800' => $assignment->status === 'in_progress',
                            'bg-yellow-100 text-yellow-800' => in_array($assignment->status, ['scheduled', 'overdue']),
                            'bg-green-100 text-green-800' => in_array($assignment->status, ['verified', 'closed']),
                            'bg-gray-100 text-gray-800' => in_array($assignment->status, ['draft', 'expired', 'declined']),
                        ])">
                                    {{ str_replace('_', ' ', ucfirst($assignment->status)) }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Location</dt>
                            <dd>{{ optional($assignment->location)->name ?? '—' }}</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Service User</dt>
                            <dd>
                                {{ optional($assignment->resident)->full_name
        ?? (optional($assignment->resident)->first_name ? optional($assignment->resident)->first_name . ' ' . optional($assignment->resident)->last_name : '—') }}
                            </dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Window</dt>
                            <dd>{{ $fmtDT($assignment->window_start) }} → {{ $fmtDT($assignment->window_end) }}</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Due</dt>
                            <dd>{{ $fmtDT($assignment->due_at) }}</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500">Requirements</dt>
                            <dd class="flex flex-wrap gap-1">
                                @if($assignment->requires_gps)
                                    <span
                                        class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs inline-flex items-center gap-1">
                                        <i class="ph ph-crosshair-simple"></i> GPS
                                    </span>
                                @endif
                                @if($assignment->requires_photo)
                                    <span
                                        class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-xs inline-flex items-center gap-1">
                                        <i class="ph ph-camera"></i> Photo
                                    </span>
                                @endif
                                @if($assignment->requires_signature)
                                    <span
                                        class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-xs inline-flex items-center gap-1">
                                        <i class="ph ph-pencil-circle"></i> Signature
                                    </span>
                                @endif
                                @if(!$assignment->requires_gps && !$assignment->requires_photo && !$assignment->requires_signature)
                                    <span class="text-xs text-gray-500">None</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-clock-counter-clockwise"></i> Timeline
                        </h3>
                        <button type="button" onclick="loadTimeline(true)"
                            class="text-xs inline-flex items-center gap-1 px-2 py-1 border border-gray-200 rounded hover:bg-gray-50">
                            <i class="ph ph-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                    <ul id="timelineList" class="divide-y divide-gray-200 max-h-96 overflow-auto">
                        <li class="p-4 text-sm text-gray-500">Loading…</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        async function verifyAndClose() {
            const form = document.getElementById('verifyForm');
            const res = await fetch('/api/assignments/{{ $assignment->id }}/verify', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(form)
            });
            if (res.ok) {
                window.location.href = '{{ route('backend.admin.assignments.index') }}';
            } else {
                try {
                    const data = await res.json();
                    alert(data.message || 'Verification failed.');
                } catch (_) { alert('Verification failed.'); }
            }
        }

        async function loadTimeline(refresh) {
            const ul = document.getElementById('timelineList');
            if (!refresh) { ul.innerHTML = '<li class="p-4 text-sm text-gray-500">Loading…</li>'; }
            const res = await fetch('/api/assignments/{{ $assignment->id }}/timeline', { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            if (!res.ok) { ul.innerHTML = '<li class="p-4 text-sm text-red-600">Failed to load timeline.</li>'; return; }
            const items = await res.json();
            if (!items.length) { ul.innerHTML = '<li class="p-4 text-sm text-gray-500">No events yet.</li>'; return; }

            ul.innerHTML = items.map(ev => {
                const at = new Date(ev.created_at).toLocaleString();
                const who = ev.actor ? ev.actor.name : 'System';
                const label = ev.event.replaceAll('_', ' ');
                return `<li class="p-4">
                <div class="text-sm text-gray-900 font-medium">${label}</div>
                <div class="text-xs text-gray-600">${who} • ${at}</div>
              </li>`;
            }).join('');
        }

        loadTimeline(false);
    </script>
@endsection