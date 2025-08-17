@extends('layouts.admin')

@section('title','Service Users — Trash')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">Service Users — Trash</h2>
    <a href="{{ route('backend.admin.service-users.index') }}" class="text-blue-600 hover:underline">← Back</a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Deleted At</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($serviceUsers as $su)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $su->full_name }}</td>
              <td class="px-4 py-2">{{ optional($su->deleted_at)->format('d M Y H:i') }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <form action="{{ route('backend.admin.service-users.restore', $su->id) }}" method="POST" class="inline-block">
                  @csrf
                  <button class="text-sm text-green-700 hover:underline">Restore</button>
                </form>
                <form action="{{ route('backend.admin.service-users.forceDelete', $su->id) }}" method="POST"
                      class="inline-block"
                      onsubmit="return confirm('Permanently delete this service user?')">
                  @csrf @method('DELETE')
                  <button class="text-sm text-red-700 hover:underline">Delete Permanently</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center px-4 py-6 text-gray-500">Trash is empty.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($serviceUsers, 'hasPages') && $serviceUsers->hasPages())
        <div class="mt-4">
          {{ $serviceUsers->links('vendor.pagination.tailwind') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
