@extends('layouts.superadmin')
@section('title', 'Trashed Tenants')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">🗑️Trashed Tenants</h2>
        <a href="{{ route('backend.super-admin.tenants.index') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            ← Back to Tenants
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Phone</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Deleted At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tenants as $tenant)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $tenant->name }}</td>
                        <td class="px-4 py-2">{{ $tenant->email }}</td>
                        <td class="px-4 py-2">{{ $tenant->phone }}</td>
                        <td class="px-4 py-2">{{ ucfirst($tenant->status) }}</td>
                        <td class="px-4 py-2">{{ $tenant->deleted_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <form action="{{ route('backend.super-admin.tenants.restore', $tenant->id) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                    Restore
                                </button>
                            </form>
                            <form action="{{ route('backend.super-admin.tenants.forceDelete', $tenant->id) }}" method="POST" onsubmit="return confirm('Permanently delete this tenant?');">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2 text-gray-500">No trashed tenants found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tenants->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection
