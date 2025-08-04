@extends('layouts.superadmin')

@section('title', 'Trashed Users')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">🗑️ Trashed Users</h2>
        <a href="{{ route('backend.super-admin.users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            ← Back to Users
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    @if($users->count())
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">#</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Name</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Email</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Deleted At</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->deleted_at->diffForHumans() }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <form action="{{ route('backend.super-admin.users.restore', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="text-green-600 hover:underline"
                                        onclick="return confirm('Restore this user?')">Restore</button>
                                </form>

                                <form action="{{ route('backend.super-admin.users.forceDelete', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:underline"
                                        onclick="return confirm('Permanently delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4">
                {{ $users->links() }}
            </div>
        </div>
    @else
        <p class="text-gray-600">No trashed users found.</p>
    @endif
</div>
@endsection
