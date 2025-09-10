@extends('layouts.admin')

@section('title','Qualifications')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">
      Qualifications — {{ $staffProfile->user->full_name ?? '—' }}
    </h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4 flex items-center justify-between">
    <a href="{{ route('backend.admin.staff-profiles.qualifications.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Add Qualification
    </a>
    <form method="GET" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search…"
             class="border border-gray-300 px-3 py-2 rounded bg-gray-50">
      <button class="px-3 py-2 border rounded hover:bg-gray-50">Go</button>
    </form>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Level</th>
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Institution</th>
            <th class="px-4 py-2">Awarded</th>
            <th class="px-4 py-2">Certificate</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($quals as $qrow)
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ $qrow->level }}</td>
              <td class="px-4 py-2">{{ $qrow->title }}</td>
              <td class="px-4 py-2">{{ $qrow->institution ?? '—' }}</td>
              <td class="px-4 py-2">{{ optional($qrow->awarded_at)->format('d M Y') ?? '—' }}</td>
              <td class="px-4 py-2">{{ $qrow->certificate_file_id ? 'Yes' : 'No' }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <a href="{{ route('backend.admin.staff-profiles.qualifications.edit', [$staffProfile, $qrow]) }}"
                   class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('backend.admin.staff-profiles.qualifications.destroy', [$staffProfile, $qrow]) }}"
                      method="POST" class="inline-block" onsubmit="return confirm('Delete this qualification?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No qualifications yet.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($quals, 'hasPages') && $quals->hasPages())
        <div class="mt-4">{{ $quals->links('vendor.pagination.tailwind') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
