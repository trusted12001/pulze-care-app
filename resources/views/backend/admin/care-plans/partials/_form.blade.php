@if($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form action="{{ $route }}" method="POST" class="bg-white rounded-lg shadow p-4 space-y-4">
  @csrf
  @if($method!=='POST') @method($method) @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Service User</label>
      <select name="service_user_id" class="border rounded w-full px-3 py-2" required>
        <option value="">Select...</option>
        @foreach($serviceUsers as $su)
        <option value="{{ $su->id }}" @selected(old('service_user_id', $plan->service_user_id ?? null)==$su->id)>
          {{ $su->full_name ?? ($su->first_name.' '.$su->last_name) }}
        </option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Title</label>
      <input name="title" value="{{ old('title',$plan->title ?? 'Comprehensive Care Plan') }}" class="border rounded w-full px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Start Date</label>
      <input type="date" name="start_date" value="{{ old('start_date', optional($plan?->start_date)->format('Y-m-d')) }}" class="border rounded w-full px-3 py-2">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Next Review Date</label>
      <input type="date" name="next_review_date" value="{{ old('next_review_date', optional($plan?->next_review_date)->format('Y-m-d')) }}" class="border rounded w-full px-3 py-2">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Review Frequency</label>
      <input name="review_frequency" placeholder="e.g. 24 weeks" value="{{ old('review_frequency',$plan->review_frequency ?? '') }}" class="border rounded w-full px-3 py-2">
    </div>

    @if($plan)
      <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="border rounded w-full px-3 py-2">
          <option value="draft" @selected(old('status',$plan->status)=='draft')>Draft</option>
          <option value="active" @selected(old('status',$plan->status)=='active')>Active</option>
          <option value="archived" @selected(old('status',$plan->status)=='archived')>Archived</option>
        </select>
      </div>
    @endif
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Summary</label>
    <textarea name="summary" rows="3" class="border rounded w-full px-3 py-2">{{ old('summary',$plan->summary ?? '') }}</textarea>
  </div>

  <div class="flex items-center gap-3">
    <button name="action" value="{{ $plan ? 'save' : 'save_draft' }}" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Save</button>
    <button name="action" value="publish" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700" onclick="return confirm('Publish and mark Active?')">Publish</button>
    <a href="{{ route('backend.admin.care-plans.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</a>
  </div>
</form>
