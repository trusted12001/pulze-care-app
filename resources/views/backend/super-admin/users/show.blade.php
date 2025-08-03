@extends('layouts.superadmin')

@section('title', 'User Details')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded border border-gray-200">
  <h2 class="text-2xl font-semibold mb-6 text-gray-800">User Details</h2>

  <div class="space-y-4 text-gray-700">
    <div>
      <strong>Name:</strong> {{ $user->name }}
    </div>
    <div>
      <strong>Email:</strong> {{ $user->email }}
    </div>
    <div>
      <strong>Status:</strong>
      <span class="inline-block px-2 py-1 rounded text-white text-xs
        {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
        {{ ucfirst($user->status) }}
      </span>
    </div>
    <div>
      <strong>Role:</strong> {{ $user->getRoleNames()->first() ?? 'None' }}
    </div>
    <div>
      <strong>Created At:</strong> {{ $user->created_at->format('d M Y, h:i A') }}
    </div>
  </div>

  <div class="mt-6">
    <a href="{{ route('backend.super-admin.users.index') }}"
       class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
      ← Back to Users
    </a>
  </div>
</div>
@endsection
