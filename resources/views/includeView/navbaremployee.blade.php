<nav class="sidebar-nav">
    <ul id="sidebarnav" class="mb-4 pb-2">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('dashboardEmployee') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                    <i class="ti ti-layout-dashboard fs-7 text-primary"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link success-hover-bg" href="{{ route('pegawai.profile') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-success rounded-3">
                    <i class="ti ti-user-circle fs-7 text-success"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Data Saya</span>
            </a>
        </li>
        <li class="sidebar-item has-sub {{ request()->is('pegawai/riwayat*') ? 'active' : '' }}">
            <a href="#" class="sidebar-link toggle-menu" data-menu-target="menu-riwayat">
            <span class="aside-icon p-2 bg-light-success rounded-3">
                <i class="ti ti-history fs-7 text-success"></i>
            </span>
            <span class="hide-menu ms-2 ps-1">Data Riwayat</span>
            <i class="ti ti-chevron-down float-end"></i>
            </a>
            <div id="menu-riwayat" class="submenu collapse {{ request()->is('pegawai/riwayat*') ? 'show' : '' }}">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_pelatihan*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_pelatihan') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Pelatihan</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_pendidikan*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_pendidikan') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Pendidikan</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_jabatan*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_jabatan') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Jabatan</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_keluarga*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_keluarga') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Keluarga</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_sipp*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_sipp') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat SIPP</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_kontrak*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_kontrak') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Kontrak</span>
                </a>
                </li>
                <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_lain*') ? 'active' : '' }}">
                <a href="{{ route('pegawai.riwayat_lain') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                    <i class="ti ti-menu fs-7 text-success"></i>
                    </span>
                    <span class="dropdown-item">Riwayat Lain-lain</span>
                </a>
                </li>
            </ul>
            </div>
        </li>

        <li class="sidebar-item has-sub {{ request()->is('pegawai/presensi*') ? 'active' : '' }}">
            <a href="#" class="sidebar-link toggle-menu" data-menu-target="menu-presensi">
            <span class="aside-icon p-2 bg-light-success rounded-3">
            <i class="ti ti-clock fs-7 text-success"></i>
            </span>
            <span class="hide-menu ms-2 ps-1">Data Presensi</span>
            <i class="ti ti-chevron-down float-end"></i>
            </a>
            <div id="menu-presensi" class="submenu collapse {{ request()->is('pegawai/presensi*') ? 'show' : '' }}">
            <ul class="nav flex-column sub-menu">
            <li class="nav-item ms-3 {{ request()->is('pegawai/presensi') ? 'active' : '' }}">
            <a href="{{ route('feature.maintenance') }}" class="sidebar-link primary-hover-bg">
                <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                <i class="ti ti-menu fs-7 text-success"></i>
                </span>
                <span class="dropdown-item">Presensi</span>
            </a>
            </li>
            <li class="nav-item ms-3 {{ request()->is('pegawai/jadwal_presensi') ? 'active' : '' }}">
            <a href="{{ route('pegawai.jadwal_presensi') }}" class="sidebar-link primary-hover-bg">
                <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                <i class="ti ti-menu fs-7 text-success"></i>
                </span>
                <span class="dropdown-item">Jadwal Presensi</span>
            </a>
            </li>
            <li class="nav-item ms-3 {{ request()->is('pegawai/riwayat_presensi') ? 'active' : '' }}">
            <a href="{{ route('pegawai.riwayat_presensi') }}" class="sidebar-link primary-hover-bg">
                <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                <i class="ti ti-menu fs-7 text-success"></i>
                </span>
                <span class="dropdown-item">Riwayat Presensi</span>
            </a>
            </li>
            </ul>
            </div>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link success-hover-bg" href="{{ route('feature.maintenance') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-success rounded-3">
                    <i class="ti ti-moneybag fs-7 text-success"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Data Gaji Saya</span>
            </a>
        </li>

        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Auth</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link danger-hover-bg" href="{{ route('logoutPegawai') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-danger rounded-3">
                    <i class="ti ti-login fs-7 text-danger"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Log Out</span>
            </a>
        </li>
    </ul>
</nav>
