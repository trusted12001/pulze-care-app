@extends('layouts.admin')
@section('title','Shift Templates')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Shift Templates</h1>

{{-- Flash messages --}}
@if(session('success'))
  <div class="mb-3 rounded bg-green-50 text-green-800 px-3 py-2 border border-green-200">{{ session('success') }}</div>
@endif
@if(session('warning'))
  <div class="mb-3 rounded bg-amber-50 text-amber-800 px-3 py-2 border border-amber-200">{{ session('warning') }}</div>
@endif
@if($errors->any())
  <div class="mb-3 rounded bg-red-50 text-red-800 px-3 py-2 border border-red-200">
    <ul class="list-disc pl-5">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="bg-white rounded shadow p-4 mb-4">
  <form method="POST" action="{{ route('backend.admin.shift-templates.store') }}" class="grid grid-cols-1 md:grid-cols-8 gap-3">
    @csrf
    <select name="location_id" class="border rounded px-3 py-2 md:col-span-2" required>
      <option value="">Location…</option>
      @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->name }}</option> @endforeach
    </select>
    <input name="name" placeholder="Early/Late/Night" class="border rounded px-3 py-2" required>
    <input name="role" placeholder="carer|nurse" class="border rounded px-3 py-2" required>
    <input type="time" name="start_time" class="border rounded px-3 py-2" required>
    <input type="time" name="end_time" class="border rounded px-3 py-2" required>
    <input type="number" name="break_minutes" placeholder="Break (m)" class="border rounded px-3 py-2">
    <input type="number" name="headcount" placeholder="Headcount" class="border rounded px-3 py-2" min="1" value="1">

    <div class="md:col-span-8">
      <div class="flex flex-wrap gap-4 items-center">
        <div>
          <div class="text-xs text-gray-500 mb-1">Days of week</div>
          <div class="flex gap-2 text-sm">
            @php $days = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun']; @endphp
            @foreach($days as $k=>$v)
              <label class="inline-flex items-center gap-1">
                <input type="checkbox" name="days_of_week[]" value="{{ $k }}"> <span>{{ $v }}</span>
              </label>
            @endforeach
            <span class="text-xs text-gray-500 ml-2">(leave all unchecked = every day)</span>
          </div>
        </div>

        <div>
          <div class="text-xs text-gray-500 mb-1">Skills (CSV)</div>
          <input name="skills" placeholder="meds,pmva,driver" class="border rounded px-3 py-2 w-64">
        </div>

        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="paid_flag" value="1" checked>
          <span>Paid</span>
        </label>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="active" value="1" checked>
          <span>Active</span>
        </label>
      </div>
    </div>

    <div class="md:col-span-8">
      <textarea name="notes" rows="2" placeholder="Notes (optional)" class="border rounded px-3 py-2 w-full"></textarea>
    </div>

    <div class="md:col-span-8">
      <button class="px-3 py-2 bg-blue-600 text-white rounded">Add Template</button>
    </div>
  </form>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="p-3 text-left">Location</th>
        <th class="p-3 text-left">Name</th>
        <th class="p-3 text-left">Role</th>
        <th class="p-3 text-left">Time</th>
        <th class="p-3 text-left">Head</th>
        <th class="p-3 text-left">Days</th>
        <th class="p-3 text-left">Active</th>
        <th class="p-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($templates as $t)
      <tr class="border-t">
        <td class="p-3">{{ $t->location->name }}</td>
        <td class="p-3">{{ $t->name }}</td>
        <td class="p-3">{{ $t->role }}</td>
        <td class="p-3">{{ $t->start_time }}–{{ $t->end_time }}
          <span class="text-xs text-gray-500">({{ $t->break_minutes }}m break)</span>
        </td>
        <td class="p-3">{{ $t->headcount ?? 1 }}</td>
        <td class="p-3">
          @php $map=['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun']; @endphp
          @if($t->days_of_week_json)
            @foreach($t->days_of_week_json as $d)
              <span class="inline-block px-2 py-0.5 bg-gray-100 rounded mr-1">{{ $map[$d] ?? strtoupper($d) }}</span>
            @endforeach
          @else
            <span class="text-gray-500">All</span>
          @endif
        </td>
        <td class="p-3">
          <span class="px-2 py-0.5 rounded text-xs {{ $t->active ? 'bg-green-100 text-green-700':'bg-gray-200 text-gray-600' }}">{{ $t->active ? 'Active':'Off' }}</span>
        </td>
        <td class="p-3 text-right space-x-1">
          <a class="text-blue-700 hover:underline" href="{{ route('backend.admin.shift-templates.edit',$t) }}">Edit</a>

          <form action="{{ route('backend.admin.shift-templates.toggle',$t) }}" method="POST" class="inline">
            @csrf
            <button class="text-amber-700 hover:underline" type="submit">{{ $t->active ? 'Deactivate':'Activate' }}</button>
          </form>

          <form action="{{ route('backend.admin.shift-templates.duplicate',$t) }}" method="POST" class="inline">
            @csrf
            <button class="text-gray-700 hover:underline" type="submit">Duplicate</button>
          </form>

          <form action="{{ route('backend.admin.shift-templates.destroy',$t) }}" method="POST" onsubmit="return confirm('Delete this template?')" class="inline">
            @csrf @method('DELETE')
            <button class="text-red-700 hover:underline" type="submit">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" class="p-4 text-center text-gray-500">No templates yet. Create one above.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $templates->links() }}</div>
@endsection
