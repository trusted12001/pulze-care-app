@extends('layouts.superadmin')

@section('title', 'Manage Users')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  {{-- Header --}}
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">Add New User</h2>
    <a href="{{ route('backend.super-admin.index') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
  </div>

  {{-- Feedback Messages --}}
  @if(session('success'))
    <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md shadow-sm">
      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 flex gap-2 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md shadow-sm">
      <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Create User Form --}}
  <div class="bg-white p-6 rounded-lg shadow-md mb-8 border border-gray-200">

    <form method="POST" action="{{ route('backend.super-admin.users.store') }}">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium text-gray-800">First Name</label>
          <input name="first_name" value="{{ old('first_name') }}" required
                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-800">Last Name</label>
          <input name="last_name" value="{{ old('last_name') }}" required
                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-800">Other Name</label>
          <input name="other_names" value="{{ old('other_names') }}"
                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-800">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required
                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-800">Password</label>
          <input type="password" name="password" required
                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-300" />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-800">Status</label>
          <select name="status" required
                  class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800">
            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="block mb-1 font-medium text-gray-800">Role</label>
          <select name="role" required
                  class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800">
            @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mt-6 text-right">
        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition duration-150 ease-in-out">
          Save User
        </button>
      </div>
    </form>
  </div>

  {{-- Registered Users Table --}}
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-semibold text-gray-800">Registered Users</h3>
      <input type="text" id="userSearch" placeholder="Search users..."
             class="border border-gray-300 px-3 py-2 rounded w-1/3 bg-gray-50" />
    </div>

    <div class="overflow-x-auto">
        <table id="usersTable" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
            <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 border-t">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $user->full_name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->getRoleNames()->first() ?? 'None' }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-block px-2 py-1 rounded text-white text-xs
                        {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-right space-x-2">
                        {{-- View --}}
                        <a href="{{ route('backend.super-admin.users.show', $user->id) }}"
                            class="text-sm text-gray-600 hover:underline">View</a>
                        {{-- Edit --}}
                        <a href="{{ route('backend.super-admin.users.edit', $user->id) }}"
                            class="text-sm text-blue-600 hover:underline">Edit</a>
                        {{-- Delete --}}
                        <form action="{{ route('backend.super-admin.users.destroy', $user->id) }}"
                            method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center px-4 py-6 text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

      {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    </div>
  </div>
    <div class="mt-4">
        <a href="{{ route('backend.super-admin.users.trashed') }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 hover:underline">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"></path>
            </svg>
            View Trash
        </a>
    </div>


</div>

{{-- Simple Live Search Script --}}
<script>
  document.getElementById('userSearch').addEventListener('keyup', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');

    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
  });
</script>
@endsection
