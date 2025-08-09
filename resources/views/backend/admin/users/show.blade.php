@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
<div class="max-w-3xl mx-auto py-10">

  <h2 class="text-2xl font-bold mb-6 text-gray-800">Staff Details</h2>

  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md space-y-4">
    <div>
      <h4 class="text-sm text-gray-500">Name</h4>
      <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
    </div>

    <div>
      <h4 class="text-sm text-gray-500">Email</h4>
      <p class="text-gray-700">{{ $user->email }}</p>
    </div>

    <div>
      <h4 class="text-sm text-gray-500">Role</h4>
      <p class="text-gray-700">{{ ucfirst($user->getRoleNames()->first() ?? 'None') }}</p>
    </div>

    <div>
      <h4 class="text-sm text-gray-500">Status</h4>
      <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                  {{ $user->status === 'active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
        {{ ucfirst($user->status) }}
      </span>
    </div>

    <div>
      <h4 class="text-sm text-gray-500">Joined</h4>
      <p class="text-gray-700">{{ $user->created_at->format('M d, Y') }}</p>
    </div>
  </div>

  <div class="mt-6">
    <a href="{{ route('backend.admin.users.index') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 hover:underline">
      ← Back to List
    </a>
  </div>
</div>
@endsection
