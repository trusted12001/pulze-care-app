@extends('layouts.admin')

@section('title', 'Trashed Users')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">🗑️ Trashed Users</h2>
        <a href="{{ route('backend.admin.users.index') }}"
           class="text-blue-600 hover:underline">← Back to Users</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md shadow-sm">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($users->count())
        {{-- Search Bar --}}
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-700">Deleted Users List</h3>
            <input type="text" id="trashSearch" placeholder="Search users..."
                   class="border border-gray-300 px-3 py-2 rounded w-1/3 bg-gray-50" />
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white p-6 rounded-lg shadow border border-gray-200">
            <table id="trashedUsersTable" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Deleted</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->deleted_at->diffForHumans() }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                {{-- Restore --}}
                                <form action="{{ route('backend.admin.users.restore', $user->id) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Restore this user?');">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700"
                                        onclick="return confirm('Restore this user?')">
                                        Restore
                                    </button>
                                </form>

                                {{-- Force Delete --}}
                                <form action="{{ route('backend.admin.users.forceDelete', $user->id) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Permanently delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @else
        <div class="text-gray-600">
            <p>No trashed users found.</p>
        </div>
    @endif
</div>

{{-- Live Search Script --}}
<script>
  document.getElementById('trashSearch').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#trashedUsersTable tbody tr');

    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
  });
</script>
@endsection
