<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->url() == route('dashboard')?'active':'' }}" href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('projects.*')?'active':'' }}" href="{{ route('projects.index') }}">Projects</a>
    </li>
</ul>