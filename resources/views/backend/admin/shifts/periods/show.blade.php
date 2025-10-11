@extends('layouts.admin')
@section('title','Rota — '.$rota_period->location->name)

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Rota: {{ $rota_period->start_date->format('d M') }} – {{ $rota_period->end_date->format('d M Y') }}</h1>
  <div class="space-x-2">

    <a href="{{ route('backend.admin.shift-templates.index', ['location_id' => $rota_period->location_id]) }}"
   class="px-3 py-2 bg-gray-200 rounded">Manage Shift Templates</a>


    <form action="{{ route('backend.admin.rota-periods.generate',$rota_period) }}" method="POST" class="inline">@csrf
      <button class="px-3 py-2 bg-gray-800 text-white rounded">Generate from Templates</button>
    </form>
    <form action="{{ route('backend.admin.rota-periods.publish',$rota_period) }}" method="POST" class="inline" onsubmit="return confirm('Publish this rota?')">@csrf
      <button class="px-3 py-2 bg-green-600 text-white rounded">Publish</button>
    </form>
    <a href="{{ route('backend.admin.rota-periods.index') }}" class="px-3 py-2 bg-gray-200 rounded">Back</a>
  </div>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50"><tr>
      <th class="p-3 text-left">Date</th>
      <th class="p-3 text-left">Role</th>
      <th class="p-3 text-left">Time</th>
      <th class="p-3 text-left">Assigned</th>
      <th class="p-3 text-right">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($rota_period->shifts->sortBy('start_at') as $s)
      <tr class="border-t">
        <td class="p-3">{{ $s->start_at->setTimezone('Europe/London')->format('D d M') }}</td>
        <td class="p-3">{{ $s->role }}</td>
        <td class="p-3">
          {{ $s->start_at->setTimezone('Europe/London')->format('H:i') }}–{{ $s->end_at->setTimezone('Europe/London')->format('H:i') }}
          <span class="text-xs text-gray-500">({{ $s->duration_minutes }}m)</span>
        </td>
        <td class="p-3">
          @forelse($s->assignments as $a)
            <span class="inline-block px-2 py-1 bg-gray-100 rounded mr-1">{{ $a->staff->name }}</span>
          @empty
            <span class="text-gray-400">—</span>
          @endforelse
        </td>
        <td class="p-3 text-right">
          <form action="{{ route('backend.admin.shifts.assign',$s) }}" method="POST" class="inline-flex items-center gap-2">
            @csrf
            <input name="staff_id" placeholder="User ID" class="border rounded px-2 py-1 w-24" required>
            <button class="px-2 py-1 bg-blue-600 text-white rounded">Assign</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="p-4 text-center text-gray-500">No shifts yet. Click Generate.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
