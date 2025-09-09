@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded border border-gray-200">
  <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Staff</h2>

  @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('backend.admin.users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1 font-medium text-gray-800">First Name</label>
        <input name="first_name" value="{{ old('first_name', $user->first_name) }}" required
               class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-800">Last Name</label>
        <input name="last_name" value="{{ old('last_name', $user->last_name) }}" required
               class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-800">Other Names</label>
        <input name="other_names" value="{{ old('other_names', $user->other_names) }}"
               class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-800">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-800">Status</label>
        <select name="status" required
                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
          <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-800">Role</label>
        <select name="role" required
                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
          @foreach($roles as $role)
            @if(in_array($role->name, ['admin', 'carer']))
              <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
              </option>
            @endif
          @endforeach
        </select>
      </div>
    </div>

    <div class="mt-6 text-right">
      <a href="{{ route('backend.admin.users.index') }}"
         class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
        Cancel
      </a>
      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
        Update Staff
      </button>
    </div>
  </form>
</div>
@endsection
