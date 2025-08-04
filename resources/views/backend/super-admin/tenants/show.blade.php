@extends('layouts.superadmin')

@section('title', 'Tenant Details')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    <h3 class="text-xl font-bold mb-4">Tenant Details</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><strong>Name:</strong> {{ $tenant->name }}</div>
        <div><strong>Email:</strong> {{ $tenant->email }}</div>
        <div><strong>Phone:</strong> {{ $tenant->phone }}</div>
        <div><strong>Address:</strong> {{ $tenant->address }}</div>
        <div><strong>Status:</strong>
            @if ($tenant->status === 'active')
                <span class="text-green-600 font-medium">Active</span>
            @else
                <span class="text-red-600 font-medium">Inactive</span>
            @endif
        </div>
        <div><strong>Created At:</strong> {{ $tenant->created_at->format('d M Y, h:i A') }}</div>
    </div>

    <div class="mt-6">
        <a href="{{ route('backend.super-admin.tenants.index') }}"
           class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
           ← Back to List
        </a>
    </div>
</div>
@endsection
