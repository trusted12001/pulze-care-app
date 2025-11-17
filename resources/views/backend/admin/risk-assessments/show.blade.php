@extends('layouts.admin')

@section('title', 'Risk Assessment')

@section('content')
  @php
    $fmtDate = fn($d) => $d ? $d->format('d M Y') : '—';
    $fmtDateTime = fn($d) => $d ? $d->format('d M Y, h:i A') : '—';

    $statusBadge = match ($assessment->status) {
      'active' => 'bg-green-100 text-green-800 border border-green-200',
      'draft' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'archived' => 'bg-gray-100 text-gray-800 border border-gray-200',
      default => 'bg-gray-100 text-gray-800 border border-gray-200',
    };

    // Risk band colors
    $riskBandColors = [
      'low' => 'bg-green-100 text-green-800 border border-green-200',
      'medium' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'high' => 'bg-orange-100 text-orange-800 border border-orange-200',
      'critical' => 'bg-red-100 text-red-800 border border-red-200',
    ];

    // For quick lookup of items per type
    $itemsByType = $itemsByType ?? collect();
  @endphp

  <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6 xl:px-8">

    {{-- Header Section --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="flex-1">
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4 mb-3">
            <div class="flex-1">
              <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 break-words">
                {{ $assessment->title }}
              </h1>
              <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $statusBadge }} font-medium">
                  <span
                    class="w-1.5 h-1.5 rounded-full {{ $assessment->status === 'active' ? 'bg-green-500' : ($assessment->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
                  {{ ucfirst($assessment->status) }}
                </span>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
              <a href="{{ route('backend.admin.risk-assessments.edit', $assessment) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-pencil-simple"></i>
                <span>Edit Assessment</span>
              </a>
              <a href="{{ route('backend.admin.risk-assessments.print', $assessment) }}" target="_blank"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-printer"></i>
                <span>Print / PDF</span>
              </a>
              <a href="{{ route('backend.admin.risk-assessments.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-arrow-left"></i>
                <span>Back to List</span>
              </a>
            </div>
          </div>
        </div>
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

    {{-- Overview Card --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
      <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
          <i class="ph ph-info text-red-600 flex-shrink-0"></i>
          <span>Assessment Overview</span>
        </h2>
      </div>
      <div class="p-4 sm:p-5 lg:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-4 sm:mb-5">
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Service
              User</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold break-words">
              {{ $assessment->serviceUser->full_name ?? ($assessment->serviceUser->first_name . ' ' . $assessment->serviceUser->last_name) }}
            </p>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
            <span
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs sm:text-sm font-medium {{ $statusBadge }}">
              <span
                class="w-1.5 h-1.5 rounded-full {{ $assessment->status === 'active' ? 'bg-green-500' : ($assessment->status === 'draft' ? 'bg-yellow-500' : 'bg-gray-500') }}"></span>
              {{ ucfirst($assessment->status) }}
            </span>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Start
              Date</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold">{{ $fmtDate($assessment->start_date) }}</p>
          </div>
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5 sm:mb-2">Next
              Review</label>
            <p class="text-sm sm:text-base text-gray-900 font-semibold">{{ $fmtDate($assessment->next_review_date) }}</p>
          </div>
        </div>

        @if($assessment->summary)
          <div class="mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-gray-200">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Summary</label>
            <div class="prose prose-sm sm:prose-base max-w-none text-gray-700 whitespace-pre-line">
              {!! nl2br(e($assessment->summary)) !!}
            </div>
          </div>
        @endif

        <div class="mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
              <i class="ph ph-user"></i>
              <span>Author: <strong class="text-gray-700">{{ $assessment->creator?->name ?? '—' }}</strong> on
                {{ $fmtDateTime($assessment->created_at) }}</span>
            </span>
            @if($assessment->updated_at)
              <span class="flex items-center gap-1.5">
                <i class="ph ph-clock"></i>
                <span>Last Updated: {{ $fmtDateTime($assessment->updated_at) }}</span>
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Risk Types Section --}}
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
      <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="ph ph-warning text-purple-600 flex-shrink-0"></i>
            <span>Risk Types & Items</span>
          </h2>
        </div>
      </div>

      <div class="p-4 sm:p-5 lg:p-6">
        {{-- Add Risk Type Form --}}
        <div class="mb-5 sm:mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
          <form method="POST" action="{{ route('backend.admin.risk-types.store', $assessment) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-9 gap-3">
              <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Risk Type <span
                    class="text-red-500">*</span></label>
                <input name="name" placeholder="e.g., Falls Risk" value="{{ old('name') }}" required
                  class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
              </div>
              <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Description</label>
                <input name="description" placeholder="Optional description..." value="{{ old('description') }}"
                  class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
              </div>
              <div class="md:col-span-2 flex items-end">
                <button type="submit"
                  class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 active:bg-purple-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                  <i class="ph ph-plus"></i> Add Risk Type
                </button>
              </div>
            </div>
          </form>
        </div>

        {{-- Risk Types Accordion --}}
        <div class="space-y-2">
          @forelse($riskTypes as $type)
            @php
              $list = $itemsByType->get($type->id, collect());
              $count = $list->count();
              $accordionId = 'rt_' . $type->id;
            @endphp

            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <button type="button"
                class="w-full flex items-center justify-between text-left px-4 py-3 bg-gradient-to-r from-indigo-50 to-blue-50 hover:from-indigo-100 hover:to-blue-100 transition-colors duration-200"
                data-acc-target="#{{ $accordionId }}">
                <div class="flex items-center gap-3">
                  <i class="ph ph-folder text-indigo-600 text-lg"></i>
                  <span class="font-semibold text-gray-900">{{ $type->name }}</span>
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ $count }} {{ $count === 1 ? 'item' : 'items' }}
                  </span>
                </div>
                <i class="ph ph-caret-down text-gray-500 transition-transform duration-200" data-acc-icon></i>
              </button>

              <div id="{{ $accordionId }}" class="hidden border-t border-gray-200">
                <div class="p-4">
                  @if($count === 0)
                    <div class="text-center py-6 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                      <i class="ph ph-warning text-gray-400 text-2xl mb-2"></i>
                      <p>No items under this risk type.</p>
                      <a href="{{ route('backend.admin.risk-items.create', ['profile' => $assessment->id, 'type' => $type->id]) }}"
                        class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="ph ph-plus"></i>
                        Add First Item
                      </a>
                    </div>
                  @else
                    <div class="overflow-x-auto">
                      <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                          <tr>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Context</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Likelihood</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Severity</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Score</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Risk Band</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-700">Status</th>
                            <th class="text-right px-3 py-2 font-semibold text-gray-700">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                          @foreach($list as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                              <td class="px-3 py-3 text-gray-900">{{ $item->context ?? '—' }}</td>
                              <td class="px-3 py-3">
                                <span
                                  class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                  {{ $item->likelihood }}
                                </span>
                              </td>
                              <td class="px-3 py-3">
                                <span
                                  class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-semibold text-sm">
                                  {{ $item->severity }}
                                </span>
                              </td>
                              <td class="px-3 py-3">
                                <span
                                  class="inline-flex items-center justify-center w-10 h-8 rounded-full bg-gray-800 text-white font-semibold text-sm">
                                  {{ $item->risk_score }}
                                </span>
                              </td>
                              <td class="px-3 py-3">
                                @php
                                  $band = strtolower($item->risk_band ?? 'low');
                                  $bandColor = $riskBandColors[$band] ?? $riskBandColors['low'];
                                @endphp
                                <span
                                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $bandColor }}">
                                  {{ ucfirst($item->risk_band) }}
                                </span>
                              </td>
                              <td class="px-3 py-3">
                                <span
                                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                  {{ ucfirst($item->status) }}
                                </span>
                              </td>
                              <td class="px-3 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                  <a href="{{ route('backend.admin.risk-items.edit', $item) }}"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded text-xs font-medium transition-colors">
                                    <i class="ph ph-pencil-simple"></i>
                                    <span>Edit</span>
                                  </a>
                                  <form method="POST" action="{{ route('backend.admin.risk-items.destroy', $item) }}"
                                    class="inline"
                                    onsubmit="return confirm('Delete this risk item? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                      class="inline-flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded text-xs font-medium transition-colors">
                                      <i class="ph ph-trash"></i>
                                      <span>Delete</span>
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                      <a href="{{ route('backend.admin.risk-items.create', ['profile' => $assessment->id, 'type' => $type->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="ph ph-plus"></i>
                        Add Item
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-8 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
              <i class="ph ph-folder-open text-gray-400 text-3xl mb-3"></i>
              <p>No risk types defined yet. Add a risk type to get started.</p>
            </div>
          @endforelse

          {{-- Uncategorized Items --}}
          @php
            $uncat = $itemsByType->get(null, collect());
          @endphp
          @if($uncat->count() > 0)
            @php $accordionId = 'rt_uncategorized'; @endphp
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <button type="button"
                class="w-full flex items-center justify-between text-left px-4 py-3 bg-gradient-to-r from-gray-50 to-slate-50 hover:from-gray-100 hover:to-slate-100 transition-colors duration-200"
                data-acc-target="#{{ $accordionId }}">
                <div class="flex items-center gap-3">
                  <i class="ph ph-folder-simple text-gray-600 text-lg"></i>
                  <span class="font-semibold text-gray-900">Uncategorized</span>
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $uncat->count() }} {{ $uncat->count() === 1 ? 'item' : 'items' }}
                  </span>
                </div>
                <i class="ph ph-caret-down text-gray-500 transition-transform duration-200" data-acc-icon></i>
              </button>

              <div id="{{ $accordionId }}" class="hidden border-t border-gray-200">
                <div class="p-4">
                  <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Context</th>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Likelihood</th>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Severity</th>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Score</th>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Risk Band</th>
                          <th class="text-left px-3 py-2 font-semibold text-gray-700">Status</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200">
                        @foreach($uncat as $item)
                          <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-3 text-gray-900">{{ $item->context ?? '—' }}</td>
                            <td class="px-3 py-3">
                              <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                {{ $item->likelihood }}
                              </span>
                            </td>
                            <td class="px-3 py-3">
                              <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-semibold text-sm">
                                {{ $item->severity }}
                              </span>
                            </td>
                            <td class="px-3 py-3">
                              <span
                                class="inline-flex items-center justify-center w-10 h-8 rounded-full bg-gray-800 text-white font-semibold text-sm">
                                {{ $item->risk_score }}
                              </span>
                            </td>
                            <td class="px-3 py-3">
                              @php
                                $band = strtolower($item->risk_band ?? 'low');
                                $bandColor = $riskBandColors[$band] ?? $riskBandColors['low'];
                              @endphp
                              <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $bandColor }}">
                                {{ ucfirst($item->risk_band) }}
                              </span>
                            </td>
                            <td class="px-3 py-3">
                              <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($item->status) }}
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Additional Sections --}}
    @includeIf('backend.admin.risk-assessments.partials._reviews', ['assessment' => $assessment])
    @includeIf('backend.admin.risk-assessments.partials._versions', ['assessment' => $assessment])
    @includeIf('backend.admin.risk-assessments.partials._signoffs', ['assessment' => $assessment])

  </div>

  {{-- Enhanced Accordion Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('[data-acc-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var sel = this.getAttribute('data-acc-target');
          var el = document.querySelector(sel);
          var icon = this.querySelector('[data-acc-icon]');
          if (!el) return;

          var isHidden = el.classList.contains('hidden');

          // Close all other accordions (optional - remove if you want multiple open)
          // document.querySelectorAll('[data-acc-target]').forEach(function(otherBtn){
          //   if(otherBtn !== btn) {
          //     var otherSel = otherBtn.getAttribute('data-acc-target');
          //     var otherEl = document.querySelector(otherSel);
          //     var otherIcon = otherBtn.querySelector('[data-acc-icon]');
          //     if(otherEl) {
          //       otherEl.classList.add('hidden');
          //       if(otherIcon) otherIcon.classList.remove('rotate-180');
          //     }
          //   }
          // });

          el.classList.toggle('hidden');
          if (icon) {
            icon.classList.toggle('rotate-180');
          }
        });
      });
    });
  </script>
@endsection