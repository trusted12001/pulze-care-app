@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
  <h2 class="heading-2">Welcome to Your Dashboard</h2>
  <p class="paragraph-small pt-3">
    You're logged in to Pulze! Use the menu to access available modules.
  </p>

  @can('view operations')
    <a href="{{ route('operations') }}" class="link-button d-inline-block mt-4">Go to Operations</a>
  @endcan

  <form method="POST" action="{{ route('logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="text-sm underline text-gray-600">
      Logout
    </button>
  </form>
@endsection
