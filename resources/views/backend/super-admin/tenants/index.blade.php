@extends('layouts.superadmin')

@section('title', 'Manage Tenants')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold text-black">Add New Tenant</h1>
    <a href="{{ route('backend.super-admin.index') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>

</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Add Tenant Form -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8 border border-gray-200">
    <form action="{{ route('backend.super-admin.tenants.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">Tenant Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300"
                   placeholder="e.g., Royal Care Ltd." required>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300"
                   placeholder="admin@carehome.com" required>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300"
                   placeholder="+44 123 456 7890" required>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Address</label>
            <input type="text" name="address" value="{{ old('address') }}"
                   class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300"
                   placeholder="123 Main St, London" required>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="md:col-span-2 text-right mt-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Add Tenant
            </button>
        </div>
    </form>
</div>

<!-- Tenants Table -->
<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-left text-sm font-medium text-gray-600">
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Phone</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
            @forelse ($tenants as $tenant)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2 font-medium">{{ $tenant->name }}</td>
                <td class="px-4 py-2">{{ $tenant->email }}</td>
                <td class="px-4 py-2">{{ $tenant->phone }}</td>
                <td class="px-4 py-2">{{ $tenant->address }}</td>
                <td class="px-4 py-2">
                    <span class="inline-block px-2 py-1 text-xs rounded
                        {{ $tenant->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('backend.super-admin.tenants.edit', $tenant->id) }}"
                        class="text-blue-600 hover:underline">Edit</a>

                    <form action="{{ route('backend.super-admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tenant?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No tenants found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 bg-gray-50 border-t">
        {{ $tenants->links('vendor.pagination.tailwind') }}
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('backend.super-admin.tenants.trashed') }}"
        class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 hover:underline">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"></path>
        </svg>
        View Trash
    </a>
</div>
@endsection
