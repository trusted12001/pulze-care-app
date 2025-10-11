@extends('layouts.admin')
@section('title','Rota Periods')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Rota Periods</h1>

<div class="bg-white rounded shadow p-4 mb-4">
  <form method="POST" action="{{ route('backend.admin.rota-periods.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
    @csrf
    <select name="location_id" class="border rounded px-3 py-2" required>
      <option value="">Location…</option>
      @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->name }}</option> @endforeach
    </select>
    <input type="date" name="start_date" class="border rounded px-3 py-2" required>
    <input type="date" name="end_date" class="border rounded px-3 py-2" required>
    <button class="px-3 py-2 bg-blue-600 text-white rounded">Create</button>
  </form>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50"><tr>
      <th class="p-3 text-left">Location</th><th class="p-3 text-left">Dates</th><th class="p-3 text-left">Status</th><th class="p-3 text-right">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($periods as $p)
      <tr class="border-t">
        <td class="p-3">{{ $p->location->name }}</td>
        <td class="p-3">{{ $p->start_date->format('d M') }} – {{ $p->end_date->format('d M Y') }}</td>
        <td class="p-3">{{ ucfirst($p->status) }}</td>
        <td class="p-3 text-right">
          <a class="text-blue-700 hover:underline" href="{{ route('backend.admin.rota-periods.show',$p) }}">Open</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" class="p-4 text-center text-gray-500">No periods.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $periods->links() }}</div>
@endsection
