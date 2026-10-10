<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 align-self-center">
        <div class="d-flex align-items-center">
            {{-- Logo Header --}}
            <img src="{{ asset('sbadmin/img/top_header.png') }}"
                 onerror="this.src='https://ui-avatars.com/api/?name=SPK+Servis&background=4e73df&color=fff'"
                 alt="Logo" style="height: 35px;">

            <div class="topbar-divider d-none d-sm-block"></div>

            {{-- Title View Dinamis --}}
            <h1 class="h5 mb-0 text-gray-800 font-weight-bold ml-2">
                @yield('title')
            </h1>

            {{-- Jargon SPK --}}
            <div class="topbar-jargon d-none d-lg-block">
                SPK Pendukung Keputusan: Optimasi Antrian Servis Pada Bengkel XYZ
            </div>
        </div>
    </div>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Bengkel</span>
                <img class="img-profile rounded-circle" src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
            </a>
        </li>
    </ul>

</nav>
