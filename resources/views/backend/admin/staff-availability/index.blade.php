@extends('layouts.admin')
@section('title','Availability')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Availability — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  <div class="mb-4">
    <a href="{{ route('backend.admin.staff-profiles.availability.create', $staffProfile) }}"
       class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
       Set Availability
    </a>
  </div>

  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
          <tr>
            <th class="px-4 py-2">Day</th>
            <th class="px-4 py-2">From</th>
            <th class="px-4 py-2">To</th>
            <th class="px-4 py-2">Preference</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @for($i=0;$i<7;$i++)
            @php $row = $byDay->get($i); @endphp
            <tr class="hover:bg-gray-50 border-t">
              <td class="px-4 py-2">{{ ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$i] }}</td>
              <td class="px-4 py-2">{{ $row?->available_from ?? '—' }}</td>
              <td class="px-4 py-2">{{ $row?->available_to ?? '—' }}</td>
              <td class="px-4 py-2">
                @php
                  $pref = $row?->preference ?? '—';
                  $badge = match($pref) {
                    'prefer' => 'bg-green-500',
                    'okay'   => 'bg-amber-500',
                    'avoid'  => 'bg-gray-500',
                    default  => 'bg-gray-400',
                  };
                @endphp
                <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">{{ ucfirst($pref) }}</span>
              </td>
              <td class="px-4 py-2 text-right space-x-2">
                @if($row)
                  <a href="{{ route('backend.admin.staff-profiles.availability.edit', [$staffProfile, $row]) }}"
                     class="text-blue-600 hover:underline">Edit</a>
                  <form action="{{ route('backend.admin.staff-profiles.availability.destroy', [$staffProfile, $row]) }}"
                        method="POST" class="inline-block" onsubmit="return confirm('Remove this day’s availability?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                  </form>
                @else
                  <a href="{{ route('backend.admin.staff-profiles.availability.create', ['staffProfile'=>$staffProfile, 'day_of_week'=>$i]) }}"
                     class="text-green-700 hover:underline">Add</a>
                @endif
              </td>
            </tr>
          @endfor
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
