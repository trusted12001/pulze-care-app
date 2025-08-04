@extends('layouts.superadmin')

@section('title', 'Edit Tenant')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-xl font-semibold mb-4  text-black">Edit Tenant</h2>

    <form action="{{ route('backend.super-admin.tenants.update', $tenant->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="slug" class="block font-semibold mb-1">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $tenant->slug) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $tenant->email) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="phone" class="block font-semibold mb-1">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="address" class="block font-semibold mb-1">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address', $tenant->address) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="status" class="block font-semibold mb-1">Status</label>
            <select name="status" id="status" class="w-full border rounded px-3 py-2">
                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Tenant</button>
        </div>
    </form>
</div>
@endsection
