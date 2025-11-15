@extends('layouts.admin')
@section('title', 'Add Risk Item')

@section('content')
{{-- Flash / Errors --}}
@if(session('success'))
  <div class="mb-3 rounded bg-green-50 text-green-800 px-3 py-2 border border-green-200">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="mb-3 rounded bg-red-50 text-red-800 px-3 py-2 border border-red-200">
    <ul class="list-disc pl-5">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Add Risk Item</h1>
  <a href="{{ isset($profile) ? route('backend.admin.risk-assessments.show', $profile) : route('backend.admin.risk-assessments.index') }}"
     class="px-3 py-2 bg-gray-200 rounded">Back</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  {{-- Main form --}}
  <div class="lg:col-span-2">
    <div class="bg-white rounded shadow p-4">
      <form method="POST"
            action="{{ isset($profile) ? route('backend.admin.risk-assessments.items.store', $profile) : route('backend.admin.risk-items.store') }}"
            class="space-y-4"
            id="risk-item-form">
        @csrf
        <input type="hidden" name="action" id="ri-action" value="save">

        {{-- When not nested, include the profile FK field --}}
        @unless(isset($profile))
          <div>
            <label class="block text-sm text-gray-600 mb-1">Risk Assessment Profile</label>
            <select name="{{ $profileFk }}" class="border rounded px-3 py-2 w-full" required>
              <option value="">Select...</option>
              @foreach($profiles as $p)
                @php
                  $su = $p->serviceUser;
                  $suName = $su
                    ? (method_exists($su,'getFullNameAttribute') ? $su->full_name : trim(($su->first_name ?? '').' '.($su->last_name ?? '')))
                    : null;
                @endphp
                <option value="{{ $p->id }}"
                  @selected(old($profileFk, request('profile')) == $p->id)>
                  {{ $suName ? $suName.' — ' : '' }}{{ $p->title }} ({{ ucfirst($p->status) }})
                </option>
              @endforeach
            </select>
          </div>
        @else
          {{-- If nested, still include the FK for validation --}}
          <input type="hidden" name="{{ $profileFk }}" value="{{ $profile->id }}">
        @endunless

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Risk Type</label>
            <select name="risk_type_id" id="risk_type_id" class="border rounded px-3 py-2 w-full" required>
              <option value="">Select...</option>
              @foreach($types as $t)
                <option value="{{ $t->id }}"
                        data-guidance="{{ e($t->default_guidance ?? '') }}"
                        data-matrix='@json($t->default_matrix ?? [])'
                        @selected(old('risk_type_id', request('type')) == $t->id)>
                  {{ $t->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Owner (optional)</label>
            <select name="owner_id" class="border rounded px-3 py-2 w-full">
              <option value="">—</option>
              @foreach($owners as $u)
                <option value="{{ $u->id }}" @selected(old('owner_id') == $u->id)>{{ $u->first_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm text-gray-600 mb-1">Hazard / Description</label>
          <input type="text"
                 name="hazard"
                 value="{{ old('hazard') }}"
                 placeholder="e.g., Slippery floor in bathroom"
                 class="border rounded px-3 py-2 w-full"
                 required>
        </div>

        {{-- Likelihood / Severity with live score --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Likelihood (1–5)</label>
            <input type="number" min="1" max="5" name="likelihood" id="likelihood"
                   value="{{ old('likelihood', 3) }}"
                   class="border rounded px-3 py-2 w-full" required>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Severity (1–5)</label>
            <input type="number" min="1" max="5" name="severity" id="severity"
                   value="{{ old('severity', 3) }}"
                   class="border rounded px-3 py-2 w-full" required>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Score</label>
            <input type="number" id="score_preview"
                   value=""
                   class="border rounded px-3 py-2 w-full bg-gray-50"
                   readonly>
          </div>
        </div>

        <div>
          <label class="block text-sm text-gray-600 mb-1">Controls / Mitigations</label>
          <textarea name="controls" rows="4" class="border rounded px-3 py-2 w-full"
                    placeholder="e.g., Non-slip mats; staff to supervise bathing; daily floor checks">{{ old('controls') }}</textarea>
        </div>

        {{-- Residuals --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Residual Likelihood (1–5)</label>
            <input type="number" min="1" max="5" name="residual_likelihood" id="residual_likelihood"
                   value="{{ old('residual_likelihood') }}"
                   class="border rounded px-3 py-2 w-full">
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Residual Severity (1–5)</label>
            <input type="number" min="1" max="5" name="residual_severity" id="residual_severity"
                   value="{{ old('residual_severity') }}"
                   class="border rounded px-3 py-2 w-full">
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Residual Score</label>
            <input type="number" id="residual_score_preview"
                   value=""
                   class="border rounded px-3 py-2 w-full bg-gray-50"
                   readonly>
          </div>
        </div>

        {{-- Status --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="border rounded px-3 py-2 w-full">
              <option value="draft"    @selected(old('status')==='draft')>Draft</option>
              <option value="active"   @selected(old('status')==='active')>Active</option>
              <option value="archived" @selected(old('status')==='archived')>Archived</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Review Date (optional)</label>
            <input type="date" name="review_date" value="{{ old('review_date') }}" class="border rounded px-3 py-2 w-full">
          </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 pt-2">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

          <button type="submit"
                  class="px-4 py-2 bg-green-600 text-white rounded"
                  onclick="document.getElementById('ri-action').value='publish'">
            Save & Publish
          </button>

          <button type="submit"
                  class="px-4 py-2 bg-gray-700 text-white rounded"
                  onclick="document.getElementById('ri-action').value='save_add_another'">
            Save & Add Another
          </button>

          <a href="{{ isset($profile) ? route('backend.admin.risk-assessments.show', $profile) : route('backend.admin.risk-assessments.index') }}"
             class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Guidance / Matrix --}}
  <div>
    <div class="bg-white rounded shadow p-4 mb-4">
      <div class="text-sm text-gray-600 mb-2">Type Guidance</div>
      <div id="guidance_box" class="prose prose-sm max-w-none text-gray-700">
        @if(isset($riskType) && $riskType->default_guidance)
          {!! nl2br(e($riskType->default_guidance)) !!}
        @else
          <span class="text-gray-500">Select a Risk Type to see guidance.</span>
        @endif
      </div>
    </div>

    <div class="bg-white rounded shadow p-4">
      <div class="text-sm text-gray-600 mb-2">Risk Matrix (if defined)</div>
      <div id="matrix_box" class="text-sm text-gray-700">
        {{-- Filled via JS from data-matrix --}}
      </div>
    </div>
  </div>
</div>

{{-- tiny inline script for previews --}}
<script>
(function(){
  const like = document.getElementById('likelihood');
  const sev  = document.getElementById('severity');
  const score = document.getElementById('score_preview');
  const rLike = document.getElementById('residual_likelihood');
  const rSev  = document.getElementById('residual_severity');
  const rScore = document.getElementById('residual_score_preview');
  const typeSel = document.getElementById('risk_type_id');
  const guidance = document.getElementById('guidance_box');
  const matrixBox = document.getElementById('matrix_box');

  function calc(a,b){ const x = parseInt(a||0,10), y = parseInt(b||0,10); return (x>0 && y>0) ? (x*y) : ''; }
  function updateScores(){
    score.value  = calc(like.value, sev.value);
    rScore.value = calc(rLike?.value, rSev?.value);
  }
  ['input','change'].forEach(evt=>{
    like.addEventListener(evt, updateScores);
    sev.addEventListener(evt, updateScores);
    if(rLike) rLike.addEventListener(evt, updateScores);
    if(rSev)  rSev.addEventListener(evt, updateScores);
  });
  updateScores();

  function renderMatrix(json){
    matrixBox.innerHTML = '';
    if(!json || typeof json !== 'object') return;
    const table = document.createElement('table');
    table.className = 'min-w-full text-xs border border-gray-200';
    const tbody = document.createElement('tbody');

    // Expecting a 2D array; render simply
    (json || []).forEach(row=>{
      const tr = document.createElement('tr');
      (row || []).forEach(cell=>{
        const td = document.createElement('td');
        td.className = 'border border-gray-200 px-2 py-1 text-center';
        td.textContent = typeof cell === 'object' ? JSON.stringify(cell) : (cell ?? '');
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });

    table.appendChild(tbody);
    matrixBox.appendChild(table);
  }

  function onTypeChange(){
    const opt = typeSel.options[typeSel.selectedIndex];
    if(!opt) return;
    const g = opt.getAttribute('data-guidance') || '';
    guidance.innerHTML = g ? g.replace(/\n/g,'<br>') : '<span class="text-gray-500">No guidance provided for this type.</span>';

    try {
      const m = JSON.parse(opt.getAttribute('data-matrix') || '[]');
      renderMatrix(m);
    } catch (e) {
      matrixBox.innerHTML = '<span class="text-gray-500">No matrix or invalid JSON.</span>';
    }
  }
  typeSel.addEventListener('change', onTypeChange);
  onTypeChange(); // initial
})();
</script>
@endsection
