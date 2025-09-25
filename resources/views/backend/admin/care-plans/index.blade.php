@extends('layouts.admin')
@section('title','Care Plans')

@section('content')
<div class="mb-6"><h1 class="text-2xl font-semibold">Care Plans</h1></div>

<div class="bg-white rounded-lg shadow p-4 mb-4">
  <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Search title..." class="border rounded px-3 py-2">
    <select name="service_user_id" class="border rounded px-3 py-2">
      <option value="">All Service Users</option>
      @foreach($serviceUsers as $su)
        <option value="{{ $su->id }}" @selected(request('service_user_id')==$su->id)>
          {{ $su->full_name ?? ($su->first_name.' '.$su->last_name) }}
        </option>
      @endforeach
    </select>
    <select name="status" class="border rounded px-3 py-2">
      <option value="">All Status</option>
      <option value="draft" @selected(request('status')==='draft')>Draft</option>
      <option value="active" @selected(request('status')==='active')>Active</option>
      <option value="archived" @selected(request('status')==='archived')>Archived</option>
    </select>
    <button class="px-3 py-2 bg-gray-800 text-white rounded">Filter</button>
  </form>
</div>

<div class="flex items-center justify-between mb-3">
  <p class="text-sm text-gray-600">Total: {{ $plans->total() }}</p>
  <a href="{{ route('backend.admin.care-plans.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">New Care Plan</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="text-left p-3">Service User</th>
        <th class="text-left p-3">Title</th>
        <th class="text-left p-3">Version</th>
        <th class="text-left p-3">Status</th>
        <th class="text-left p-3">Next Review</th>
        <th class="text-right p-3">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($plans as $p)
      <tr class="border-t">
        <td class="p-3">{{ $p->serviceUser->full_name ?? '' }}</td>
        <td class="p-3">{{ $p->title }}</td>
        <td class="p-3">v{{ $p->version }}</td>
        <td class="p-3">
          <span class="px-2 py-1 rounded text-xs
            @if($p->status==='active') bg-green-100 text-green-700
            @elseif($p->status==='draft') bg-yellow-100 text-yellow-700
            @else bg-gray-100 text-gray-700 @endif">{{ ucfirst($p->status) }}</span>
        </td>
        <td class="p-3">{{ $p->next_review_date? \Illuminate\Support\Carbon::parse($p->next_review_date)->format('d M Y') : '—' }}</td>
        <td class="p-3 text-right space-x-2">
          <a href="{{ route('backend.admin.care-plans.show',$p) }}" class="px-2 py-1 text-blue-700 hover:underline">View</a>
          <a href="{{ route('backend.admin.care-plans.edit',$p) }}" class="px-2 py-1 text-amber-700 hover:underline">Edit</a>
          <form action="{{ route('backend.admin.care-plans.destroy',$p) }}" method="POST" class="inline-block" onsubmit="return confirm('Move to trash?')">
            @csrf @method('DELETE')
            <button class="px-2 py-1 text-red-700 hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="p-4 text-center text-gray-500">No care plans yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $plans->links() }}</div>
@endsection
