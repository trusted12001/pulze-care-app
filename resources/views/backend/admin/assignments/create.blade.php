@extends('layouts.admin')

@section('title', 'Create Assignment')

@section('content')
    @php
        $dtVal = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('Y-m-d\TH:i') : '';
    @endphp

    <div class="min-h-screen p-3 sm:p-4 lg:p-6">
        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">New Assignment</h1>
                    <p class="text-sm sm:text-base text-gray-600">Create a task with optional GPS, photo and signature
                        requirements.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('backend.admin.assignments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="ph ph-arrow-left"></i><span>Back</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="ph ph-clipboard-text text-blue-600"></i>
                    <span>Assignment Details</span>
                </h3>
            </div>

            <form id="createAssignmentForm" class="p-4 sm:p-6" onsubmit="return false;">
                @csrf

                {{-- Errors --}}
                <div id="errorBox" class="hidden mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    {{-- Left --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input name="title" required
                                class="mt-1 w-full rounded-lg border border-gray-800 focus:ring-2 focus:ring-orange-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" required
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                <option value="care">Care</option>
                                <option value="documentation">Documentation</option>
                                <option value="operations">Operations</option>
                                <option value="training">Training</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assign To (Staff)</label>
                            <select class="form-control" name="assigned_to" required>
                                @foreach($staff as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->display_name ?: $u->email }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <select name="location_id" required
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Service User (optional)</label>
                            <select name="resident_id"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500">
                                <option value="">— None —</option>
                                @foreach($residents as $r)
                                    <option value="{{ $r->id }}">
                                        {{ method_exists($r, 'getFullNameAttribute') ? $r->full_name : ($r->first_name . ' ' . $r->last_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4"
                                class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500"
                                placeholder="What should be done, how, and any risks/precautions…"></textarea>
                        </div>
                    </div>

                    {{-- Right --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Window Start</label>
                                <input type="datetime-local" name="window_start"
                                    class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Window End</label>
                                <input type="datetime-local" name="window_end"
                                    class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Due At</label>
                                <input type="datetime-local" name="due_at"
                                    class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SLA (mins)</label>
                                <input type="number" min="0" name="sla_minutes" value="0"
                                    class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500" />
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                <i class="ph ph-flag-checkered"></i> Requirements
                            </h4>
                            <div class="flex flex-col gap-2">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="requires_gps" class="rounded border  border-gray-700"
                                        checked>
                                    <span class="text-sm text-gray-700">Require GPS (prevents posting outside
                                        location)</span>
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="requires_photo" class="rounded border  border-gray-700">
                                    <span class="text-sm text-gray-700">Require Photo Evidence</span>
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="requires_signature"
                                        class="rounded border  border-gray-700">
                                    <span class="text-sm border text-gray-700">Require On-screen Signature</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recurrence (iCal RRULE)</label>
                            <input name="recurrence_rule" placeholder="FREQ=DAILY;COUNT=5"
                                class="mt-1 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500" />
                            <p class="text-xs text-gray-500 mt-1">Optional. Example: <code>FREQ=WEEKLY;BYDAY=MO,WE,FR</code>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('backend.admin.assignments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="ph ph-x"></i> Cancel
                    </a>
                    <button type="button" onclick="submitCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="ph ph-paper-plane-tilt"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Inline script --}}
    <script>
        async function submitCreate() {
            const form = document.getElementById('createAssignmentForm');
            const errorBox = document.getElementById('errorBox');

            // reset error box
            errorBox.classList.add('hidden');
            errorBox.innerHTML = '';

            const fd = new FormData(form);

            // Ensure checkboxes always send 1/0
            ['requires_gps', 'requires_photo', 'requires_signature'].forEach(k => {
                const input = form.querySelector(`input[name="${k}"]`);
                fd.set(k, input && input.checked ? '1' : '0');
            });

            const res = await fetch("{{ url('/backend/admin/assignments') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: fd
            });

            if (res.ok) {
                window.location.href = "{{ route('backend.admin.assignments.index') }}";
                return;
            }

            // ------------ Improved Error Handling --------------
            let bodyText = await res.text();
            let data = null;

            try {
                data = JSON.parse(bodyText);
            } catch (e) {
                // bodyText is not JSON (probably HTML exception page)
            }

            if (data && data.errors) {
                const list = Object.entries(data.errors)
                    .flatMap(([field, msgs]) => msgs.map(m => "<li><strong>" + field + "</strong>: " + m + "</li>"))
                    .join("");
                errorBox.innerHTML = "<ul class='list-disc list-inside'>" + list + "</ul>";
            } else if (data && data.message) {
                errorBox.textContent = data.message;
            } else {
                errorBox.innerHTML =
                    "<p class='mb-2'>Failed to create assignment (status " + res.status + ").</p>" +
                    "<pre class='text-xs whitespace-pre-wrap bg-gray-100 p-2 rounded'>" +
                    bodyText.replace(/</g, "&lt;") +
                    "</pre>";
            }

            errorBox.classList.remove('hidden');
        }
    </script>


@endsection