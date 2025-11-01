@extends('layouts.admin')
@section('title','Risk Types')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Risk Types</h1>
  <a href="{{ route('backend.admin.risk-types.create') }}"
     class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Risk Type</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="text-left p-3">Name</th>
        <th class="text-left p-3">Description</th>
      </tr>
    </thead>
    <tbody>
      @forelse($types as $t)
        <tr class="border-t">
          <td class="p-3">{{ $t->name }}</td>
          <td class="p-3">{{ $t->description }}</td>
        </tr>
      @empty
        <tr><td colspan="2" class="p-4 text-center text-gray-500">No risk types yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $types->links() }}</div>
@endsection
