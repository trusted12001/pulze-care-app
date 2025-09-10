<hr class="border-gray-200">

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="bg-white border border-gray-200 rounded p-4">
    <div class="flex items-center justify-between mb-2">
      <h3 class="font-semibold text-gray-800">Contracts</h3>
      <a href="{{ route('backend.admin.staff-profiles.contracts.index', $staffProfile) }}" class="text-blue-600 hover:underline text-sm">Manage</a>
    </div>
    @php $latest = $staffProfile->contracts()->latest('start_date')->first(); @endphp
    @if($latest)
      <p class="text-sm text-gray-700">
        <strong>Type:</strong> {{ ucwords(str_replace('_',' ', $latest->contract_type)) }}<br>
        <strong>Start:</strong> {{ optional($latest->start_date)->format('d M Y') }}<br>
        <strong>End:</strong> {{ optional($latest->end_date)->format('d M Y') ?? '—' }}
      </p>
    @else
      <p class="text-sm text-gray-500">No contracts yet.</p>
    @endif
  </div>

  <div class="bg-white border border-gray-200 rounded p-4">
    <div class="flex items-center justify-between mb-2">
      <h3 class="font-semibold text-gray-800">Registrations</h3>
      <a href="{{ route('backend.admin.staff-profiles.registrations.index', $staffProfile) }}" class="text-blue-600 hover:underline text-sm">Manage</a>
    </div>
    @php $reg = $staffProfile->registrations()->orderByRaw('status="active" desc, expires_at asc')->first(); @endphp
    @if($reg)
      <p class="text-sm text-gray-700">
        <strong>Body:</strong> {{ $reg->body }}<br>
        <strong>Status:</strong> {{ ucfirst($reg->status) }}<br>
        <strong>Expires:</strong> {{ optional($reg->expires_at)->format('d M Y') ?? '—' }}
      </p>
    @else
      <p class="text-sm text-gray-500">No registrations yet.</p>
    @endif
  </div>
</div>
