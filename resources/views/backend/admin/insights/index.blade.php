@extends('layouts.superadmin')
@section('title','Insights • Risks')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Insights — Risks</h1>

<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
  <div class="bg-white rounded shadow p-3"><div class="text-xs text-gray-500">Active</div><div class="text-2xl font-semibold">{{ $totals['all'] }}</div></div>
  <div class="bg-white rounded shadow p-3"><div class="text-xs text-gray-500">High</div><div class="text-2xl font-semibold">{{ $totals['high'] }}</div></div>
  <div class="bg-white rounded shadow p-3"><div class="text-xs text-gray-500">Medium</div><div class="text-2xl font-semibold">{{ $totals['med'] }}</div></div>
  <div class="bg-white rounded shadow p-3"><div class="text-xs text-gray-500">Low</div><div class="text-2xl font-semibold">{{ $totals['low'] }}</div></div>
  <div class="bg-white rounded shadow p-3"><div class="text-xs text-gray-500">Overdue</div><div class="text-2xl font-semibold">{{ $totals['overdue'] }}</div></div>
</div>

<div class="bg-white rounded-lg shadow p-4 mb-4">
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
    <select name="band" class="border rounded px-3 py-2">
      <option value="">All Bands</option>
      <option value="high" @selected($band==='high')>High</option>
      <option value="medium" @selected($band==='medium')>Medium</option>
      <option value="low" @selected($band==='low')>Low</option>
    </select>

    <select name="type" class="border rounded px-3 py-2">
      <option value="">All Types</option>
      @foreach($types as $t)
        <option value="{{ $t->id }}" @selected($type==$t->id)>{{ $t->name }}</option>
      @endforeach
    </select>

    <select name="overdue" class="border rounded px-3 py-2">
      <option value="">Any Due</option>
      <option value="1" @selected($overdue)>Overdue only</option>
    </select>

    <button class="px-3 py-2 bg-gray-800 text-white rounded">Apply</button>
  </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="text-left p-3">Service User</th>
        <th class="text-left p-3">Title</th>
        <th class="text-left p-3">Type</th>
        <th class="text-left p-3">Score</th>
        <th class="text-left p-3">Band</th>
        <th class="text-left p-3">Next Review</th>
      </tr>
    </thead>
    <tbody>
      @forelse($list as $a)
      <tr class="border-t">
        <td class="p-3">{{ $a->serviceUser->full_name ?? '' }}</td>
        <td class="p-3">{{ $a->title }}</td>
        <td class="p-3">{{ $a->riskType?->name }}</td>
        <td class="p-3 font-semibold">{{ $a->risk_score }}</td>
        <td class="p-3">@include('backend.admin.risk-assessments.partials.band-badge',['band'=>$a->risk_band])</td>
        <td class="p-3">
          @if($a->next_review_date)
            {{ \Illuminate\Support\Carbon::parse($a->next_review_date)->format('d M Y') }}
            @if($a->is_overdue)
              <span class="ml-1 px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">Overdue {{ $a->overdue_days }}d</span>
            @endif
          @else — @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="p-4 text-center text-gray-500">No matches.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $list->links() }}</div>
@endsection
