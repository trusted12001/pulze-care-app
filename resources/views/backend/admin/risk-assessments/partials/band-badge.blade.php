@php
  $map = [
    'low' => 'bg-green-100 text-green-700',
    'medium' => 'bg-amber-100 text-amber-700',
    'high' => 'bg-red-100 text-red-700',
  ];
@endphp
<span class="px-2 py-1 rounded text-xs {{ $map[$band] ?? 'bg-gray-100 text-gray-700' }}">
  {{ ucfirst($band) }}
</span>
