@extends('layouts.admin')
@section('title','Risk Assessments')

@section('content')
<div class="mb-6">
  <h1 class="text-2xl font-semibold">Risk Assessments</h1>
</div>

{{-- Section 1: Filter Card --}}
<div class="bg-white rounded-lg shadow p-4 mb-4">
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
    <input name="search"
           value="{{ request('search') }}"
           placeholder="Search title..."
           class="border rounded px-3 py-2">

    <select name="service_user_id" class="border rounded px-3 py-2">
      <option value="">All Service Users</option>
      @foreach($serviceUsers as $su)
        <option value="{{ $su->id }}" @selected(request('service_user_id')==$su->id)>
          {{ method_exists($su,'getFullNameAttribute') ? $su->full_name : ($su->first_name.' '.$su->last_name) }}
        </option>
      @endforeach
    </select>

    <select name="status" class="border rounded px-3 py-2">
      <option value="">All Status</option>
      <option value="draft"    @selected(request('status')==='draft')>Draft</option>
      <option value="active"   @selected(request('status')==='active')>Active</option>
      <option value="archived" @selected(request('status')==='archived')>Archived</option>
    </select>

    <button class="px-3 py-2 bg-gray-800 text-white rounded">Filter</button>
  </form>
</div>

{{-- Section 2: Header Row (Total left, New right) --}}
<div class="flex items-center justify-between mb-3">
  <p class="text-sm text-gray-600">Total: {{ $assessments->total() }}</p>
  <a href="{{ route('backend.admin.risk-assessments.create') }}"
     class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    New Assessment
  </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="text-left p-3">Service User</th>
        <th class="text-left p-3">Title</th>
        <th class="text-left p-3">Status</th>
        <th class="text-left p-3">Updated</th>
        <th class="text-right p-3">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($assessments as $a)
        <tr class="border-t">
          <td class="p-3">
            {{ optional($a->serviceUser)->full_name
                ?? optional($a->serviceUser)->first_name.' '.optional($a->serviceUser)->last_name }}
          </td>
          <td class="p-3">{{ $a->title ?? '—' }}</td>
          <td class="p-3">
            <span class="px-2 py-1 rounded text-xs
              @if($a->status==='active') bg-green-100 text-green-700
              @elseif($a->status==='draft') bg-yellow-100 text-yellow-700
              @else bg-gray-100 text-gray-700 @endif">
              {{ ucfirst($a->status ?? '—') }}
            </span>
          </td>
          <td class="p-3">
            {{ $a->updated_at ? \Illuminate\Support\Carbon::parse($a->updated_at)->format('d M Y') : '—' }}
          </td>
          <td class="p-3">
            {{-- Open goes to SHOW (from there they can see/add sections, update, etc.) --}}
            <a href="{{ route('backend.admin.risk-assessments.show', $a) }}"
               class="px-2 py-1 text-green-600 font-bold hover:underline text-sm">Open
            </a>

            {{-- Edit --}}
            <a href="{{ route('backend.admin.risk-assessments.edit', $a) }}"
                class="px-2 py-1 text-blue-600 hover:underline text-sm">
                Edit
            </a>


          {{-- Delete (inline form with confirm) --}}
          <form method="POST"
                action="{{ route('backend.admin.risk-assessments.destroy', $a) }}"
                onsubmit="return confirm('Delete this risk assessment? This action cannot be undone.');"
                class="inline">   {{-- Tailwind: display:inline --}}
                @csrf
                @method('DELETE')
                <button type="submit" class="px-2 py-1 text-red-600 hover:underline text-sm">
                    Delete
                </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="p-4 text-center text-gray-500">No risk assessments yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $assessments->links() }}
</div>
@endsection
