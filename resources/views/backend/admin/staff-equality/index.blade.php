@extends('layouts.admin')

@section('title','Equality Data')

@section('content')
<div class="min-h-screen p-0 rounded-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-black">Equality Data — {{ $staffProfile->user->full_name ?? '—' }}</h2>
    <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}" class="text-blue-600 hover:underline">← Back to Staff</a>
  </div>

  @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

  @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
  @endif

  @if($equality)
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200 space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><span class="text-sm text-gray-500">Ethnicity</span><div class="text-gray-800">{{ $equality->ethnicity ?? '—' }}</div></div>
        <div><span class="text-sm text-gray-500">Religion</span><div class="text-gray-800">{{ $equality->religion ?? '—' }}</div></div>
        <div><span class="text-sm text-gray-500">Disability</span>
             <div>
               @php $badge=$equality->disability?'bg-green-500':'bg-gray-500'; $label=$equality->disability?'Yes':'No'; @endphp
               <span class="inline-block px-2 py-1 rounded text-white text-xs {{ $badge }}">{{ $label }}</span>
             </div>
        </div>
        <div><span class="text-sm text-gray-500">Gender Identity</span><div class="text-gray-800">{{ $equality->gender_identity ?? '—' }}</div></div>
        <div><span class="text-sm text-gray-500">Sexual Orientation</span><div class="text-gray-800">{{ $equality->sexual_orientation ?? '—' }}</div></div>
        <div><span class="text-sm text-gray-500">Data Source</span><div class="text-gray-800">{{ ucfirst(str_replace('_',' ',$equality->data_source)) }}</div></div>
      </div>

      <div class="text-right">
        <a href="{{ route('backend.admin.staff-profiles.equality.edit', [$staffProfile, $equality]) }}"
           class="text-blue-600 hover:underline">Edit</a>
        <form action="{{ route('backend.admin.staff-profiles.equality.destroy', [$staffProfile, $equality]) }}"
              method="POST" class="inline-block ml-3"
              onsubmit="return confirm('Remove equality data?')">
          @csrf @method('DELETE')
          <button class="text-red-600 hover:underline">Delete</button>
        </form>
      </div>
    </div>
  @else
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
      <p class="text-gray-700">No equality data recorded.</p>
      <div class="mt-4">
        <a href="{{ route('backend.admin.staff-profiles.equality.create', $staffProfile) }}"
           class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Add Equality Data</a>
      </div>
    </div>
  @endif
</div>
@endsection
