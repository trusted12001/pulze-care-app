@extends('layouts.admin')

@section('title', 'Assignments')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-sm">
            <div class="flex items-center gap-2 text-sm sm:text-base">
                <i class="ph ph-check-circle text-green-600"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 rounded-lg shadow-sm">
            <div class="flex items-center gap-2 text-sm sm:text-base">
                <i class="ph ph-warning-circle text-red-600"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-sm">
            <div class="flex items-center gap-2 text-sm sm:text-base">
                <i class="ph ph-info text-yellow-600"></i>
                <span class="font-medium">{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-600 text-blue-800 rounded-lg shadow-sm">
            <div class="flex items-center gap-2 text-sm sm:text-base">
                <i class="ph ph-info text-blue-600"></i>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        </div>
    @endif

    @php
        $fmtDateTime = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M d, Y · H:i') : '—';
    @endphp

    <div class="min-h-screen p-3 sm:p-4 lg:p-6">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Assignments</h1>
                    <p class="text-sm sm:text-base text-gray-600">Create, track, and verify staff tasks with GPS & shift
                        checks.</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                    <a href="{{ route('backend.admin.assignments.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                        <i class="ph ph-plus-circle"></i><span>New Assignment</span>
                    </a>
                    <a href="{{ route('backend.admin.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                        <i class="ph ph-arrow-left"></i><span>Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- My Assignments --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 sm:mb-8">
            <div class=" bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <button onclick="toggleCreateForm()"
                    class="w-full flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-colors duration-200">

                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-clipboard-text text-blue-600"></i>
                        <span>My Assignments</span>
                        <span class="text-sm font-normal text-gray-500">({{ $mine->total() }})</span>
                    </h3>

                    <div class="hidden sm:flex items-center gap-2">
                        <a href="{{ route('backend.admin.assignments.index', ['filter' => 'today']) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg bg-white border border-gray-200 hover:bg-gray-50">
                            <i class="ph ph-calendar"></i> Today
                        </a>
                        <a href="{{ route('backend.admin.assignments.index', ['filter' => 'overdue']) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg bg-white border border-gray-200 hover:bg-gray-50">
                            <i class="ph ph-hourglass"></i> Overdue
                        </a>
                    </div>
                    <i id="createFormIcon"
                        class="ph ph-caret-down text-gray-600 text-xl transition-transform duration-200"></i>
                </button>
            </div>


            {{-- Desktop Table --}}
            <div id="assignmentForm" class="hidden overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Title</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Resident</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Priority</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Due</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mine as $a)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($a->title, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm sm:text-base font-medium text-gray-900 truncate">{{ $a->title }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($a->type) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($a->resident)->full_name ?? '—' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($a->location)->name ?? '—' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                                                                                                                                                                                                                                              @class([
                                                                                                                                                                                                                                                                'bg-gray-100 text-gray-800' => $a->priority === 'low',
                                                                                                                                                                                                                                                                'bg-amber-100 text-amber-800' => $a->priority === 'medium',
                                                                                                                                                                                                                                                                'bg-red-100 text-red-800' => $a->priority === 'high',
                                                                                                                                                                                                                                                            ])">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full @class(['bg-gray-500' => $a->priority === 'low', 'bg-amber-500' => $a->priority === 'medium', 'bg-red-500' => $a->priority === 'high'])"></span>
                                        {{ ucfirst($a->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $fmtDateTime($a->due_at) }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                                                                                                                                                                                                                                              @class([
                                                                                                                                                                                                                                                                'bg-yellow-100 text-yellow-800' => $a->status === 'scheduled' || $a->status === 'overdue',
                                                                                                                                                                                                                                                                'bg-blue-100 text-blue-800' => $a->status === 'in_progress',
                                                                                                                                                                                                                                                                'bg-purple-100 text-purple-800' => $a->status === 'submitted',
                                                                                                                                                                                                                                                                'bg-green-100 text-green-800' => in_array($a->status, ['verified', 'closed']),
                                                                                                                                                                                                                                                                'bg-gray-100 text-gray-800' => in_array($a->status, ['draft', 'expired', 'declined'])
                                                                                                                                                                                                                                                            ])">
                                        <span class="w-1.5 h-1.5 rounded-full @class([
                                            'bg-yellow-500' => $a->status === 'scheduled' || $a->status === 'overdue',
                                            'bg-blue-500' => $a->status === 'in_progress',
                                            'bg-purple-500' => $a->status === 'submitted',
                                            'bg-green-500' => in_array($a->status, ['verified', 'closed']),
                                            'bg-gray-500' => in_array($a->status, ['draft', 'expired', 'declined'])
                                        ])"></span>
                                        {{ str_replace('_', ' ', ucfirst($a->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('backend.admin.assignments.show', $a) }}"
                                            class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            title="Open">
                                            <i class="ph ph-play"></i><span class="hidden lg:inline">Open</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 sm:px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="ph ph-clipboard-text text-4xl text-gray-300"></i>
                                        <p class="text-gray-500 text-sm sm:text-base">No assignments yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-200">
                @forelse($mine as $a)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                {{ strtoupper(substr($a->title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-base font-semibold text-gray-900 truncate">{{ $a->title }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ optional($a->resident)->full_name ?? '—' }} •
                                            {{ optional($a->location)->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                                                                                                                                                                                                                                          @class([
                                                                                                                                                                                                                                                            'bg-gray-100 text-gray-800' => $a->priority === 'low',
                                                                                                                                                                                                                                                            'bg-amber-100 text-amber-800' => $a->priority === 'medium',
                                                                                                                                                                                                                                                            'bg-red-100 text-red-800' => $a->priority === 'high',
                                                                                                                                                                                                                                                        ])">{{ ucfirst($a->priority) }}</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="text-xs text-gray-600"><i class="ph ph-calendar"></i> Due
                                        {{ $fmtDateTime($a->due_at) }}</span>
                                    <span class="text-xs">•</span>
                                    <span class="text-xs text-gray-600 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full @class([
                                            'bg-yellow-500' => $a->status === 'scheduled' || $a->status === 'overdue',
                                            'bg-blue-500' => $a->status === 'in_progress',
                                            'bg-purple-500' => $a->status === 'submitted',
                                            'bg-green-500' => in_array($a->status, ['verified', 'closed']),
                                            'bg-gray-500' => in_array($a->status, ['draft', 'expired', 'declined'])
                                        ])"></span>
                                        {{ str_replace('_', ' ', ucfirst($a->status)) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                    <a href="{{ route('backend.admin.assignments.show', $a) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors text-sm font-medium">
                                        <i class="ph ph-play"></i> Open
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="ph ph-clipboard-text text-4xl text-gray-300"></i>
                            <p class="text-gray-500 text-sm sm:text-base">No assignments yet.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($mine->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $mine->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>


        {{-- 🔹 All Assignments (Admin overview) --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-clipboard text-slate-700"></i>
                        <span>All Assignments</span>
                        <span class="text-sm font-normal text-gray-500">({{ $all->total() }})</span>
                    </h3>
                </div>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Title</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Resident</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Assigned To</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Due</th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th colspan="2"
                                class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($all as $a)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 sm:px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($a->title, 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                                            {{ $a->title }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ ucfirst($a->type) }} • {{ $a->code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ optional($a->resident)->full_name ?? '—' }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ optional($a->location)->name ?? '—' }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ optional($a->assignee)->first_name
                            ? $a->assignee->first_name . ' ' . $a->assignee->last_name
                            : ($a->assignee->email ?? '—') }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $fmtDateTime($a->due_at) }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @class([
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    'bg-yellow-100 text-yellow-800' => $a->status === 'scheduled' || $a->status === 'overdue',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    'bg-blue-100 text-blue-800' => $a->status === 'in_progress',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    'bg-purple-100 text-purple-800' => $a->status === 'submitted',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    'bg-green-100 text-green-800' => in_array($a->status, ['verified', 'closed']),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    'bg-gray-100 text-gray-800' => in_array($a->status, ['draft', 'expired', 'declined']),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ])">
                                                    <span class="w-1.5 h-1.5 rounded-full @class([
                                                        'bg-yellow-500' => $a->status === 'scheduled' || $a->status === 'overdue',
                                                        'bg-blue-500' => $a->status === 'in_progress',
                                                        'bg-purple-500' => $a->status === 'submitted',
                                                        'bg-green-500' => in_array($a->status, ['verified', 'closed']),
                                                        'bg-gray-500' => in_array($a->status, ['draft', 'expired', 'declined']),
                                                    ])"></span>
                                                    {{ str_replace('_', ' ', ucfirst($a->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">

                                                    {{-- View / Open --}}
                                                    <a href="{{ route('backend.admin.assignments.show', $a) }}"
                                                        class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                        title="Open">
                                                        <i class="ph ph-play"></i>
                                                        <span class="hidden lg:inline">Open</span>
                                                    </a>

                                                    {{-- Edit: only if policy allows and assignment is not closed --}}
                                                    @can('update', $a)
                                                        @if(!in_array($a->status, ['closed']))
                                                            <a href="{{ route('backend.admin.assignments.edit', $a) }}"
                                                                class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors duration-200"
                                                                title="Edit assignment">
                                                                <i class="ph ph-pencil-simple"></i>
                                                                <span class="hidden lg:inline">Edit</span>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    {{-- Delete: only if policy allows and status is still safe to delete --}}
                                                    @php
                                                        // Normalise status just in case (e.g. 'Scheduled' vs 'scheduled')
                                                        $status = strtolower($a->status ?? '');
                                                    @endphp

                                                    @php
    $status = strtolower($a->status ?? '');
@endphp

@can('delete', $a)
    {{-- Only allow delete when not yet closed/verified, tweak as you like --}}
    @if(in_array($status, ['scheduled', 'draft']))
        <form
            action="{{ route('backend.admin.assignments.destroy', $a) }}"
            method="POST"
            class="inline-block"
            onsubmit="return confirm('Delete this assignment?');"
        >
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                title="Delete assignment"
            >
                <i class="ph ph-trash"></i>
                <span class="hidden lg:inline">Delete</span>
            </button>
        </form>
    @endif
@endcan


                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 sm:px-6 py-8 text-center text-gray-500">
                                    No assignments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-200">
                @forelse($all as $a)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($a->title, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-base font-semibold text-gray-900 truncate">{{ $a->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ optional($a->resident)->full_name ?? '—' }} •
                                                {{ optional($a->location)->name ?? '—' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Assigned to:
                                                {{ optional($a->assignee)->first_name
                    ? $a->assignee->first_name . ' ' . $a->assignee->last_name
                    : ($a->assignee->email ?? '—') }}
                                            </p>
                                        </div>
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                                                                                                                                                                                                                                                                                                                                                                @class([
                                                                                                                                                                                                                                                                                                                                                                                    'bg-gray-100 text-gray-800' => $a->priority === 'low',
                                                                                                                                                                                                                                                                                                                                                                                    'bg-amber-100 text-amber-800' => $a->priority === 'medium',
                                                                                                                                                                                                                                                                                                                                                                                    'bg-red-100 text-red-800' => $a->priority === 'high',
                                                                                                                                                                                                                                                                                                                                                                                ])">
                                            {{ ucfirst($a->priority) }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="text-xs text-gray-600">
                                            <i class="ph ph-calendar"></i> Due {{ $fmtDateTime($a->due_at) }}
                                        </span>
                                        <span class="text-xs">•</span>
                                        <span class="text-xs text-gray-600 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full @class([
                                                'bg-yellow-500' => $a->status === 'scheduled' || $a->status === 'overdue',
                                                'bg-blue-500' => $a->status === 'in_progress',
                                                'bg-purple-500' => $a->status === 'submitted',
                                                'bg-green-500' => in_array($a->status, ['verified', 'closed']),
                                                'bg-gray-500' => in_array($a->status, ['draft', 'expired', 'declined']),
                                            ])"></span>
                                            {{ str_replace('_', ' ', ucfirst($a->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 pt-2 border-top border-gray-100">
                                        <a href="{{ route('backend.admin.assignments.show', $a) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors text-sm font-medium">
                                            <i class="ph ph-play"></i> Open
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <div class="p-12 text-center text-gray-500">
                        No assignments found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination for All Assignments --}}
            @if($all->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $all->appends(request()->except('all_page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>


        {{-- Verification Queue --}}
        @can('backend.admin.assignments.verify')
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-gray-200">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ph ph-check-circle text-emerald-600"></i>
                        <span>Verification Queue</span>
                        @if($queue instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <span class="text-sm font-normal text-gray-500">({{ $queue->total() }})</span>
                        @endif
                    </h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @if($queue instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        @forelse($queue as $q)
                            <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $q->title }}</div>
                                    <div class="text-xs text-gray-500">Submitted {{ $q->updated_at->diffForHumans() }}</div>
                                </div>
                                <a href="{{ route('backend.admin.assignments.verify', $q) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 active:bg-emerald-800 text-sm font-medium">
                                    <i class="ph ph-check"></i> Verify
                                </a>
                            </div>
                        @empty
                            <div class="px-4 sm:px-6 py-8 text-center text-gray-500">Nothing to verify right now.</div>
                        @endforelse

                        @if($queue->hasPages())
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $queue->links('vendor.pagination.tailwind') }}
                            </div>
                        @endif
                    @else
                        <div class="px-4 sm:px-6 py-8 text-center text-gray-500">You do not have items in the verification queue.
                        </div>
                    @endif
                </div>
            </div>
        @endcan

    </div>



    {{-- Search Script --}}
    <script>
        function toggleCreateForm() {
            const form = document.getElementById('assignmentForm');
            const icon = document.getElementById('createFormIcon');
            form.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function showDeleteModal(userId, userName, userEmail) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const nameElement = document.getElementById('deleteUserName');
            const emailElement = document.getElementById('deleteUserEmail');

            form.action = '{{ route("backend.admin.users.destroy", ":id") }}'.replace(':id', userId);
            nameElement.textContent = userName;
            emailElement.textContent = userEmail;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });

        document.getElementById('userSearch').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr, .user-card');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        // Auto-expand form if there are errors
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('assignmentForm');
                const icon = document.getElementById('createFormIcon');
                if (form) {
                    form.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                }
            });
        @endif
    </script>
@endsection
