@extends('layouts.admin')

@section('title','Driving Licences')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Driving Licences — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.driving-licences.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Add Licence</a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Licence #</th>
            <th class="px-4 py-2">Categories</th>
            <th class="px-4 py-2">Expires</th>
            <th class="px-4 py-2">Business Insurance</th>
            <th class="px-4 py-2">Document</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            @php
              $lic = $it->licence_number ?: '—';
              $masked = $it->licence_number ? (strlen($it->licence_number) > 4 ? str_repeat('•', strlen($it->licence_number)-4) . substr($it->licence_number, -4) : $it->licence_number) : '—';
              $exp = optional($it->expires_at)->format('d M Y') ?? '—';
              $insBadge = $it->business_insurance_confirmed ? 'bg-green-500' : 'bg-gray-500';
              $insLbl   = $it->business_insurance_confirmed ? 'Yes' : 'No';
            @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $masked }}</td>
              <td class="px-4 py-2">{{ $it->categories ?: '—' }}</td>
              <td class="px-4 py-2">{{ $exp }}</td>
              <td class="px-4 py-2">
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $insBadge }}">{{ $insLbl }}</span>
              </td>
              <td class="px-4 py-2">{{ $it->document_file_id ? 'Yes' : '—' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.driving-licences.edit', [$staffProfile, $it]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.driving-licences.destroy', [$staffProfile, $it]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this licence record?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No driving licences yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($items, 'hasPages') && $items->hasPages())
        <div class="mt-4">{{ $items->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
