@extends('layouts.admin')

@section('title', 'Edit Risk Item')

@section('content')
  @php
    $riskBandColors = [
      'low' => 'bg-green-100 text-green-800',
      'medium' => 'bg-yellow-100 text-yellow-800',
      'high' => 'bg-orange-100 text-orange-800',
      'critical' => 'bg-red-100 text-red-800',
    ];
  @endphp

  <div class="max-w-6xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Edit Risk Item</h1>
          <p class="text-sm sm:text-base text-gray-600">Update risk assessment item details</p>
        </div>
        <a href="{{ route('backend.admin.risk-assessments.show', $riskItem->{$profileFk}) }}"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
          <i class="ph ph-arrow-left"></i>
          <span>Back to Assessment</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
      {{-- Main Form --}}
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-pencil-simple text-red-600 flex-shrink-0"></i>
              <span>Risk Item Details</span>
            </h2>
          </div>

          <form method="POST" action="{{ route('backend.admin.risk-items.update', $riskItem) }}"
            class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5" id="risk-item-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" id="ri-action" value="save">

            {{-- Profile Selection --}}
            <div>
              <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">
                Risk Assessment Profile <span class="text-red-500">*</span>
              </label>
              <select name="{{ $profileFk }}"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                required>
                @foreach($profiles as $p)
                  @php
                    $su = $p->serviceUser;
                    $suName = $su
                      ? (method_exists($su, 'getFullNameAttribute') ? $su->full_name : trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? '')))
                      : null;
                  @endphp
                  <option value="{{ $p->id }}" @selected(old($profileFk, $riskItem->{$profileFk}) == $p->id)>
                    {{ $suName ? $suName . ' — ' : '' }}{{ $p->title }} ({{ ucfirst($p->status) }})
                  </option>
                @endforeach
              </select>
              @error($profileFk)
                <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Risk Type & Owner --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">
                  Risk Type <span class="text-red-500">*</span>
                </label>
                <select name="risk_type_id" id="risk_type_id"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                  required>
                  @foreach($types as $t)
                    <option value="{{ $t->id }}" data-guidance="{{ e($t->default_guidance ?? '') }}"
                      data-matrix='@json($t->default_matrix ?? [])' @selected(old('risk_type_id', $riskItem->risk_type_id) == $t->id)>
                      {{ $t->name }}
                    </option>
                  @endforeach
                </select>
                @error('risk_type_id')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Risk
                  Officer / Owner</label>
                <select name="owner_id"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                  <option value="">—</option>
                  @foreach($owners as $u)
                    <option value="{{ $u->id }}" @selected(old('owner_id', $riskItem->owner_id) == $u->id)>
                      {{ $u->first_name }}</option>
                  @endforeach
                </select>
                @error('owner_id')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- Context --}}
            <div>
              <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Context
                (where/when/trigger)</label>
              <input type="text" name="context" value="{{ old('context', $riskItem->context) }}"
                placeholder="e.g., Attempts to leave after 19:00 when visitors depart"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
              @error('context')
                <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Hazard --}}
            <div>
              <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">
                Hazard / Description <span class="text-red-500">*</span>
              </label>
              <input type="text" name="hazard" value="{{ old('hazard', $riskItem->hazard) }}"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                required>
              @error('hazard')
                <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Likelihood / Severity / Score --}}
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
              <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="ph ph-calculator"></i>
                Risk Scoring
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Likelihood (1–5) <span class="text-red-500">*</span>
                  </label>
                  <input type="number" min="1" max="5" name="likelihood" id="likelihood"
                    value="{{ old('likelihood', $riskItem->likelihood) }}"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    required>
                  @error('likelihood')
                    <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">
                    Severity (1–5) <span class="text-red-500">*</span>
                  </label>
                  <input type="number" min="1" max="5" name="severity" id="severity"
                    value="{{ old('severity', $riskItem->severity) }}"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                    required>
                  @error('severity')
                    <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">Risk Score</label>
                  <input type="number" id="score_preview" value=""
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base font-semibold text-gray-900"
                    readonly>
                  <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                </div>
              </div>
            </div>

            {{-- Controls --}}
            <div>
              <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Controls /
                Mitigations</label>
              <textarea name="controls" rows="4"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">{{ old('controls', $riskItem->controls) }}</textarea>
              @error('controls')
                <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Residual Risk Scoring --}}
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
              <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="ph ph-shield-check"></i>
                Residual Risk (After Controls)
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">Residual Likelihood (1–5)</label>
                  <input type="number" min="1" max="5" name="residual_likelihood" id="residual_likelihood"
                    value="{{ old('residual_likelihood', $riskItem->residual_likelihood) }}"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  @error('residual_likelihood')
                    <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">Residual Severity (1–5)</label>
                  <input type="number" min="1" max="5" name="residual_severity" id="residual_severity"
                    value="{{ old('residual_severity', $riskItem->residual_severity) }}"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                  @error('residual_severity')
                    <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1.5 sm:mb-2">Residual Score</label>
                  <input type="number" id="residual_score_preview" value=""
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base font-semibold text-gray-900"
                    readonly>
                  <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                </div>
              </div>
            </div>

            {{-- Status --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
              <div>
                <label
                  class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-2">Status</label>
                @php $status = old('status', $riskItem->status ?? 'draft'); @endphp
                <select name="status"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                  <option value="draft" @selected($status === 'draft')>Draft</option>
                  <option value="active" @selected($status === 'active')>Active</option>
                  <option value="archived" @selected($status === 'archived')>Archived</option>
                </select>
                @error('status')
                  <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- Form Actions --}}
            <div
              class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-5 border-t border-gray-200">
              <a href="{{ route('backend.admin.risk-assessments.show', $riskItem->{$profileFk}) }}"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors duration-200 text-sm sm:text-base font-medium">
                <i class="ph ph-x"></i>
                <span>Cancel</span>
              </a>
              <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium"
                onclick="document.getElementById('ri-action').value='publish'; return confirm('Publish and mark as Active?')">
                <i class="ph ph-check-circle"></i>
                <span>Save & Publish</span>
              </button>
              <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors duration-200 shadow-sm hover:shadow-md text-sm sm:text-base font-medium">
                <i class="ph ph-floppy-disk"></i>
                <span>Save Changes</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Sidebar --}}
      <div class="space-y-4 sm:space-y-5 lg:space-y-6">
        {{-- Guidance Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-info text-blue-600 flex-shrink-0"></i>
              <span>Type Guidance</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div id="guidance_box" class="prose prose-sm max-w-none text-gray-700">
              <span class="text-gray-500">Select a Risk Type to see guidance.</span>
            </div>
          </div>
        </div>

        {{-- Risk Matrix Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
              <i class="ph ph-table text-purple-600 flex-shrink-0"></i>
              <span>Risk Matrix</span>
            </h2>
          </div>
          <div class="p-4 sm:p-5 lg:p-6">
            <div id="matrix_box" class="text-sm text-gray-700">
              <span class="text-gray-500">Select a Risk Type to see matrix.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- Score Calculation & Type Guidance Script --}}
  <script>
    (function () {
      const like = document.getElementById('likelihood');
      const sev = document.getElementById('severity');
      const score = document.getElementById('score_preview');
      const rLike = document.getElementById('residual_likelihood');
      const rSev = document.getElementById('residual_severity');
      const rScore = document.getElementById('residual_score_preview');
      const typeSel = document.getElementById('risk_type_id');
      const guidance = document.getElementById('guidance_box');
      const matrixBox = document.getElementById('matrix_box');

      function calc(a, b) { const x = parseInt(a || 0, 10), y = parseInt(b || 0, 10); return (x > 0 && y > 0) ? (x * y) : ''; }

      function updateScores() {
        score.value = calc(like.value, sev.value);
        rScore.value = calc(rLike?.value, rSev?.value);
      }

      ['input', 'change'].forEach(evt => {
        like.addEventListener(evt, updateScores);
        sev.addEventListener(evt, updateScores);
        if (rLike) rLike.addEventListener(evt, updateScores);
        if (rSev) rSev.addEventListener(evt, updateScores);
      });
      updateScores();

      function renderMatrix(json) {
        matrixBox.innerHTML = '';
        if (!json || typeof json !== 'object') return;
        const table = document.createElement('table');
        table.className = 'min-w-full text-xs border border-gray-200 rounded-lg overflow-hidden';
        const tbody = document.createElement('tbody');

        (json || []).forEach(row => {
          const tr = document.createElement('tr');
          (row || []).forEach(cell => {
            const td = document.createElement('td');
            td.className = 'border border-gray-200 px-2 py-1 text-center bg-white';
            td.textContent = typeof cell === 'object' ? JSON.stringify(cell) : (cell ?? '');
            tr.appendChild(td);
          });
          tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        matrixBox.appendChild(table);
      }

      function onTypeChange() {
        const opt = typeSel.options[typeSel.selectedIndex];
        if (!opt) return;
        const g = opt.getAttribute('data-guidance') || '';
        guidance.innerHTML = g ? g.replace(/\n/g, '<br>') : '<span class="text-gray-500">No guidance provided for this type.</span>';

        try {
          const m = JSON.parse(opt.getAttribute('data-matrix') || '[]');
          if (m && m.length > 0) {
            renderMatrix(m);
          } else {
            matrixBox.innerHTML = '<span class="text-gray-500">No matrix defined for this type.</span>';
          }
        } catch (e) {
          matrixBox.innerHTML = '<span class="text-gray-500">No matrix or invalid JSON.</span>';
        }
      }
      typeSel.addEventListener('change', onTypeChange);
      onTypeChange(); // initial
    })();
  </script>
@endsection