<nav class="flex gap-4 p-3">
  <a href="{{ route('dashboard') }}">Dashboard</a>
  @can('view operations')
    <a href="{{ route('operations') }}">Operations</a>
  @endcan
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
  </form>
</nav>
