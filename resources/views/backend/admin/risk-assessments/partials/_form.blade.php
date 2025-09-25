@if ($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
  </div>
@endif

<form action="{{ $route }}" method="POST" class="bg-white rounded-lg shadow p-4 space-y-4">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Service User</label>
      <select name="service_user_id" class="border rounded w-full px-3 py-2" required>
        <option value="">Select...</option>
        @foreach($serviceUsers as $su)
          <option value="{{ $su->id }}"
            @selected(old('service_user_id', $assessment->service_user_id ?? null) == $su->id)>
            {{ $su->full_name ?? $su->first_name.' '.$su->last_name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Risk Type</label>
      <select name="risk_type_id" class="border rounded w-full px-3 py-2" required>
        <option value="">Select...</option>
        @foreach($riskTypes as $rt)
          <option value="{{ $rt->id }}"
            @selected(old('risk_type_id', $assessment->risk_type_id ?? null) == $rt->id)>
            {{ $rt->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm font-medium mb-1">Title</label>
      <input type="text" name="title" value="{{ old('title', $assessment->title ?? '') }}"
             class="border rounded w-full px-3 py-2" required>
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm font-medium mb-1">Context / Notes</label>
      <textarea name="context" rows="4" class="border rounded w-full px-3 py-2">{{ old('context', $assessment->context ?? '') }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Likelihood (1-5)</label>
      <input type="number" name="likelihood" min="1" max="5"
             value="{{ old('likelihood', $assessment->likelihood ?? 1) }}"
             class="border rounded w-full px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Severity (1-5)</label>
      <input type="number" name="severity" min="1" max="5"
             value="{{ old('severity', $assessment->severity ?? 1) }}"
             class="border rounded w-full px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Next Review Date</label>
      <input type="date" name="next_review_date"
             value="{{ old('next_review_date', optional($assessment?->next_review_date)->format('Y-m-d')) }}"
             class="border rounded w-full px-3 py-2">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Review Frequency</label>
      <input type="text" name="review_frequency" placeholder="e.g. 12 weeks"
             value="{{ old('review_frequency', $assessment->review_frequency ?? '') }}"
             class="border rounded w-full px-3 py-2">
    </div>

    @if($assessment)
      <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="border rounded w-full px-3 py-2">
          <option value="draft" @selected(old('status',$assessment->status)=='draft')>Draft</option>
          <option value="active" @selected(old('status',$assessment->status)=='active')>Active</option>
          <option value="archived" @selected(old('status',$assessment->status)=='archived')>Archived</option>
        </select>
      </div>
      <div class="flex items-end">
        <span class="text-sm text-gray-600">Score & Band will auto-update on Save.</span>
      </div>
    @endif
  </div>

  <div class="flex items-center gap-3">
    <button name="action" value="{{ $assessment ? 'save' : 'save_draft' }}"
            class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Save</button>

    <button name="action" value="{{ $assessment ? 'publish' : 'publish' }}"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            onclick="return confirm('Publish and mark Active?')">Publish</button>

    <a href="{{ route('backend.admin.risk-assessments.index') }}"
       class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</a>
  </div>
</form>
