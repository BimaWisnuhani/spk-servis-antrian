<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wrench"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SPK Servis</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Sistem
    </div>

    <!-- Data Antrian Servis -->
    <li class="nav-item {{ request()->is('antrian*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/antrian') }}">
            <i class="fas fa-fw fa-list-alt"></i>
            <span>Data Antrian Servis</span>
        </a>
    </li>

    <!-- Bobot Kriteria -->
    <li class="nav-item {{ request()->is('bobot-kriteria*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/bobot-kriteria') }}">
            <i class="fas fa-fw fa-balance-scale"></i>
            <span>Bobot Kriteria</span>
        </a>
    </li>

    <!-- Sub Kriteria -->
    <li class="nav-item {{ request()->is('sub-kriteria*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/sub-kriteria') }}">
            <i class="fas fa-fw fa-layer-group"></i>
            <span>Sub Kriteria</span>
        </a>
    </li>

    <!-- Perhitungan & Ranking -->
    <li class="nav-item {{ request()->is('perhitungan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/perhitungan') }}">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Perhitungan & Ranking</span>
        </a>
    </li>

    <!-- Nav Item - Riwayat Servis -->
    <li class="nav-item {{ request()->is('riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('riwayat.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Servis</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading Sistem -->
    <div class="sidebar-heading">
        Akun
    </div>

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-sign-out-alt text-danger"></i>
            <span>Logout</span>
        </a>
    </li>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar Minimizer SB Admin 2) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
