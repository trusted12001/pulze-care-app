@extends('layouts.admin')
@section('title','Edit Shift Template')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Edit Template — {{ $shift_template->name }}</h1>

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

<div class="bg-white rounded shadow p-4">
  <form method="POST" action="{{ route('backend.admin.shift-templates.update',$shift_template) }}" class="grid grid-cols-1 md:grid-cols-8 gap-3">
    @csrf @method('PUT')
    <select name="location_id" class="border rounded px-3 py-2 md:col-span-2" required>
      @foreach($locations as $loc)
        <option value="{{ $loc->id }}" @selected($shift_template->location_id==$loc->id)>{{ $loc->name }}</option>
      @endforeach
    </select>
    <input name="name" value="{{ old('name',$shift_template->name) }}" class="border rounded px-3 py-2" required>
    <input name="role" value="{{ old('role',$shift_template->role) }}" class="border rounded px-3 py-2" required>
    <input type="time" name="start_time" value="{{ old('start_time',$shift_template->start_time) }}" class="border rounded px-3 py-2" required>
    <input type="time" name="end_time" value="{{ old('end_time',$shift_template->end_time) }}" class="border rounded px-3 py-2" required>
    <input type="number" name="break_minutes" value="{{ old('break_minutes',$shift_template->break_minutes) }}" class="border rounded px-3 py-2" min="0">
    <input type="number" name="headcount" value="{{ old('headcount',$shift_template->headcount ?? 1) }}" class="border rounded px-3 py-2" min="1">

    <div class="md:col-span-8">
      <div class="flex flex-wrap gap-4 items-center">
        <div>
          <div class="text-xs text-gray-500 mb-1">Days of week</div>
          <div class="flex gap-2 text-sm">
            @php $days = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun']; @endphp
            @foreach($days as $k=>$v)
              <label class="inline-flex items-center gap-1">
                <input type="checkbox" name="days_of_week[]" value="{{ $k }}"
                  @checked(collect($shift_template->days_of_week_json)->contains($k))>
                <span>{{ $v }}</span>
              </label>
            @endforeach
            <span class="text-xs text-gray-500 ml-2">(no selection = every day)</span>
          </div>
        </div>

        <div>
          <div class="text-xs text-gray-500 mb-1">Skills (CSV)</div>
          <input name="skills" value="{{ old('skills', is_array($shift_template->skills_json) ? implode(',', $shift_template->skills_json) : '') }}"
                 class="border rounded px-3 py-2 w-64" placeholder="meds,pmva,driver">
        </div>

        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="paid_flag" value="1" @checked($shift_template->paid_flag)>
          <span>Paid</span>
        </label>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="active" value="1" @checked($shift_template->active)>
          <span>Active</span>
        </label>
      </div>
    </div>

    <div class="md:col-span-8">
      <textarea name="notes" rows="3" class="border rounded px-3 py-2 w-full" placeholder="Notes...">{{ old('notes',$shift_template->notes) }}</textarea>
    </div>

    <div class="md:col-span-8">
      <button class="px-3 py-2 bg-blue-600 text-white rounded">Save Changes</button>
      <a href="{{ route('backend.admin.shift-templates.index') }}" class="px-3 py-2 bg-gray-200 rounded">Back</a>
    </div>
  </form>
</div>
@endsection
