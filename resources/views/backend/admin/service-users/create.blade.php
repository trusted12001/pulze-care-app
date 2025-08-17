@extends('layouts.admin')

@section('title','Create Service User')

@section('content')
<div class="min-h-screen p-0 rounded-lg">

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-black">Create Service User</h2>
    <a href="{{ route('backend.admin.service-users.index') }}" class="text-blue-600 hover:underline">← Back</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md shadow-sm">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('backend.admin.service-users.store') }}"
        class="bg-white p-6 rounded-lg shadow border border-gray-200">
    @csrf
    @include('backend.admin.service-users._form')
  </form>

</div>
@endsection
