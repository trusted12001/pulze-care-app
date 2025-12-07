@extends('layouts.admin')

@section('title', 'Rota Table — ' . $rota_period->location->name)

@section('content')
    <div class="min-h-screen p-3 sm:p-4 lg:p-6">
        {{-- Header --}}
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('backend.admin.rota-periods.show', $rota_period) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                    <i class="ph ph-arrow-left"></i>
                    <span>Back to Period</span>
                </a>
                <a href="{{ route('backend.admin.rota-periods.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                    <i class="ph ph-list"></i>
                    <span>All Periods</span>
                </a>
                <a href="{{ route('backend.admin.rota-periods.print', $rota_period) }}" target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium shadow-sm">
                    <i class="ph ph-printer"></i>
                    <span>Print</span>
                </a>
            </div>

        </div>

        {{-- Rota Table --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="ph ph-table text-teal-600"></i>
                    <span>Rota Overview</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Morning · Afternoon · Night shifts with assigned staff.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Morning
                                <span class="block text-[10px] text-gray-400 font-normal">~07:00–14:00</span>
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Afternoon
                                <span class="block text-[10px] text-gray-400 font-normal">~14:00–21:00</span>
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Night
                                <span class="block text-[10px] text-gray-400 font-normal">~21:00–07:00</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($days as $day)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Date --}}
                                <td class="px-4 sm:px-6 py-3 align-top whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $day['date']->format('D d M') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $day['date']->format('Y') }}
                                    </div>
                                </td>

                                {{-- Helper --}}
                                @php
                                    $segments = $day['segments'];
                                    $segmentOrder = ['morning', 'afternoon', 'night'];
                                @endphp

                                {{-- Segments --}}
                                @foreach($segmentOrder as $seg)
                                    <td class="px-4 sm:px-6 py-3 align-top">
                                        @if(!empty($segments[$seg]))
                                            <div class="flex flex-col gap-1.5">
                                                @foreach($segments[$seg] as $entry)
                                                    <div class="rounded-lg bg-gray-50 border border-gray-100 px-2.5 py-1.5">
                                                        <div class="text-xs font-semibold text-gray-800">
                                                            {{ $entry['label'] }}
                                                        </div>
                                                        @if(!empty($entry['staff']))
                                                            <div class="mt-1 flex flex-wrap gap-1">
                                                                @foreach($entry['staff'] as $name)
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded-full bg-teal-50 text-[11px] text-teal-700 border border-teal-100">
                                                                        <i class="ph ph-user-circle text-[12px] mr-1"></i>
                                                                        {{ $name }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="mt-1 text-[11px] text-gray-400 italic">
                                                                No staff assigned
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-300 italic">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 sm:px-6 py-10 text-center text-gray-500">
                                    No shifts generated for this period yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection