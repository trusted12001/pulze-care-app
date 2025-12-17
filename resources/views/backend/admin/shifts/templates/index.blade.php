@extends('layouts.admin')

@section('title', 'Shift Templates')

@section('content')

    <div class="min-h-screen p-3 sm:p-4 lg:p-6">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Shift Templates</h1>
                    <p class="text-sm sm:text-base text-gray-600">Manage shift templates for rota generation</p>
                </div>
                <a href="{{ route('backend.admin.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                    <i class="ph ph-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-sm sm:text-base">
                    <i class="ph ph-check-circle text-green-600 flex-shrink-0"></i>
                    <span class="break-words">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif


        @if(session('warning'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-800 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-sm sm:text-base">
                    <i class="ph ph-warning text-amber-600 flex-shrink-0"></i>
                    <span class="break-words">{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                <div class="flex items-start gap-2 text-sm sm:text-base">
                    <i class="ph ph-warning-circle text-red-600 mt-0.5 flex-shrink-0"></i>
                    <div class="min-w-0 flex-1">
                        <strong class="font-semibold block mb-1">Please fix the following:</strong>
                        <ul class="list-disc ml-4 sm:ml-5 space-y-1">
                            @foreach($errors->all() as $e)
                                <li class="break-words">{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Create Template Form --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="ph ph-plus-circle text-amber-600"></i>
                    <span>Create New Template</span>
                </h2>
            </div>
            <form method="POST" action="{{ route('backend.admin.shift-templates.store') }}" class="p-4 sm:p-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Location <span
                                class="text-red-500">*</span></label>
                        <select name="location_id"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            required>
                            <option value="">Select Location…</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Name <span
                                class="text-red-500">*</span></label>
                        <input name="name" placeholder="Early/Late/Night"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            required />
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Role <span
                                class="text-red-500">*</span></label>
                        <input name="role" placeholder="carer|nurse"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            required />
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Start Time <span
                                class="text-red-500">*</span></label>
                        <input type="time" name="start_time"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            required />
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">End Time <span
                                class="text-red-500">*</span></label>
                        <input type="time" name="end_time"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            required />
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Break (minutes)</label>
                        <input type="number" name="break_minutes" min="0"
                            value="{{ old('break_minutes', $shift_template->break_minutes ?? 0) }}" placeholder="Break (m)"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" />
                    </div>
                    <div>
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Headcount</label>
                        <input type="number" name="headcount" placeholder="Headcount"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            min="1" value="1" />
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Days of Week</label>
                        <div class="flex flex-wrap gap-3 sm:gap-4">
                            @php $days = ['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun']; @endphp
                            @foreach($days as $k => $v)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="days_of_week[]" value="{{ $k }}"
                                        class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm text-gray-700">{{ $v }}</span>
                                </label>
                            @endforeach
                            <span class="text-xs text-gray-500 self-center">(leave all unchecked = every day)</span>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Skills (CSV)</label>
                        <input name="skills" placeholder="meds,pmva,driver"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" />
                    </div>
                    <div class="sm:col-span-2 flex items-center gap-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="paid_flag" value="1" checked
                                class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm text-gray-700">Paid</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="active" value="1" checked
                                class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block mb-1.5 sm:mb-2 text-sm font-medium text-gray-700">Notes (optional)</label>
                        <textarea name="notes" rows="2" placeholder="Notes (optional)"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"></textarea>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 active:bg-amber-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                            <i class="ph ph-plus-circle"></i>
                            <span>Add Template</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Templates List Section --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="ph ph-clock-afternoon text-amber-600"></i>
                    <span>All Templates</span>
                    <span class="text-sm font-normal text-gray-500">({{ $templates->total() }})</span>
                </h3>
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Time</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Head</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Days</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Active</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($templates as $t)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm sm:text-base font-medium text-gray-900">{{ $t->location->name }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm sm:text-base font-medium text-gray-900">{{ $t->name }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs sm:text-sm font-medium">
                                        {{ $t->role }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm sm:text-base text-gray-900">{{ $t->start_time }}–{{ $t->end_time }}
                                    </div>
                                    @if($t->break_minutes)
                                        <div class="text-xs text-gray-500">({{ $t->break_minutes }}m break)</div>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm sm:text-base text-gray-900">{{ $t->headcount ?? 1 }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    @php $map = ['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun']; @endphp
                                    @if($t->days_of_week_json)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($t->days_of_week_json as $d)
                                                <span
                                                    class="inline-block px-2 py-0.5 bg-gray-100 rounded text-xs">{{ $map[$d] ?? strtoupper($d) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">All</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $t->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $t->active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                        {{ $t->active ? 'Active' : 'Off' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('backend.admin.shift-templates.edit', $t) }}"
                                            class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            title="Edit">
                                            <i class="ph ph-pencil-simple"></i>
                                            <span class="hidden lg:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('backend.admin.shift-templates.toggle', $t) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors duration-200"
                                                title="{{ $t->active ? 'Deactivate' : 'Activate' }}">
                                                <i class="ph ph-{{ $t->active ? 'pause' : 'play' }}"></i>
                                                <span
                                                    class="hidden lg:inline">{{ $t->active ? 'Deactivate' : 'Activate' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('backend.admin.shift-templates.duplicate', $t) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors duration-200"
                                                title="Duplicate">
                                                <i class="ph ph-copy"></i>
                                                <span class="hidden lg:inline">Duplicate</span>
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="showDeleteModal({{ $t->id }}, '{{ addslashes($t->name) }}')"
                                            class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            title="Delete">
                                            <i class="ph ph-trash"></i>
                                            <span class="hidden lg:inline">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 sm:px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="ph ph-clock-afternoon text-4xl text-gray-300"></i>
                                        <p class="text-gray-500 text-sm sm:text-base">No templates yet. Create one above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-200">
                @forelse($templates as $t)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150 template-card">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-900">{{ $t->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $t->location->name }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $t->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $t->active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                    {{ $t->active ? 'Active' : 'Off' }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">
                                    {{ $t->role }}
                                </span>
                                <span class="text-xs text-gray-600">{{ $t->start_time }}–{{ $t->end_time }}</span>
                                @if($t->break_minutes)
                                    <span class="text-xs text-gray-500">({{ $t->break_minutes }}m break)</span>
                                @endif
                                <span class="text-xs text-gray-600">Head: {{ $t->headcount ?? 1 }}</span>
                            </div>
                            @php $map = ['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun']; @endphp
                            @if($t->days_of_week_json)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($t->days_of_week_json as $d)
                                        <span
                                            class="inline-block px-2 py-0.5 bg-gray-100 rounded text-xs">{{ $map[$d] ?? strtoupper($d) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-500">All days</span>
                            @endif
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                <a href="{{ route('backend.admin.shift-templates.edit', $t) }}"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors text-sm font-medium">
                                    <i class="ph ph-pencil-simple"></i>
                                    Edit
                                </a>
                                <form action="{{ route('backend.admin.shift-templates.toggle', $t) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 active:bg-amber-200 transition-colors text-sm font-medium">
                                        <i class="ph ph-{{ $t->active ? 'pause' : 'play' }}"></i>
                                        {{ $t->active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('backend.admin.shift-templates.duplicate', $t) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors text-sm font-medium">
                                        <i class="ph ph-copy"></i>
                                        Duplicate
                                    </button>
                                </form>
                                <button type="button" onclick="showDeleteModal({{ $t->id }}, '{{ addslashes($t->name) }}')"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition-colors text-sm font-medium">
                                    <i class="ph ph-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="ph ph-clock-afternoon text-4xl text-gray-300"></i>
                            <p class="text-gray-500 text-sm sm:text-base">No templates yet. Create one above.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($templates->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $templates->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideDeleteModal()"></div>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ph ph-warning text-red-600 text-2xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">Delete Shift
                                    Template</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this template?</p>
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm font-medium text-gray-900" id="deleteTemplateName"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                <i class="ph ph-trash"></i>
                                Delete Template
                            </button>
                            <button type="button" onclick="hideDeleteModal()"
                                class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                <i class="ph ph-x"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Script --}}
        <script>
            function showDeleteModal(templateId, templateName) {
                const modal = document.getElementById('deleteModal');
                const form = document.getElementById('deleteForm');
                const nameElement = document.getElementById('deleteTemplateName');

                form.action = '{{ route("backend.admin.shift-templates.destroy", ":id") }}'.replace(':id', templateId);
                nameElement.textContent = templateName;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideDeleteModal() {
                const modal = document.getElementById('deleteModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideDeleteModal();
                }
            });
        </script>
@endsection