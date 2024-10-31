<nav class="sidebar-nav">
    <ul id="sidebarnav" class="mb-4 pb-2">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('dashboard') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                    <i class="ti ti-layout-dashboard fs-7 text-primary"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link warning-hover-bg" href="{{ route('employee.index') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-warning rounded-3">
                    <i class="ti ti-article fs-7 text-warning"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Data Pegawai</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link danger-hover-bg" href="#ui-basic" data-bs-toggle="collapse" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-danger rounded-3">
                    <i class="ti ti-alert-circle fs-7 text-danger"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Master Data</span>
                <i class="ti ti-chevron-down float-end"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('unit.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                                <i class="ti ti-building fs-7 text-success"></i>
                            </span>
                            <span class="dropdown-item">Jabatan Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('profesi.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-info rounded-3">
                                <i class="ti ti-user fs-7 text-info"></i>
                            </span>
                            <span class="dropdown-item">Profesi Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('pendidikan.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-warning rounded-3">
                                <i class="ti ti-book fs-7 text-warning"></i>
                            </span>
                            <span class="dropdown-item">Pendidikan Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('golongan.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-danger rounded-3">
                                <i class="ti ti-tag fs-7 text-danger"></i>
                            </span>
                            <span class="dropdown-item">Golongan Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('statuskaryawan.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-secondary rounded-3">
                                <i class="ti ti-check-circle fs-7 text-secondary"></i>
                            </span>
                            <span class="dropdown-item">Status Karyawan</span>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('statuskeluarga.index') }}">
                            <span class="aside-icon p-2 me-2 bg-light-dark rounded-3">
                                <i class="ti ti-users fs-7 text-dark"></i>
                            </span>
                            <span class="dropdown-item">Status Keluarga</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Pelatihan/Diklat</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link danger-hover-bg" href="{{ route('jenispelatihan.index') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-danger rounded-3">
                    <i class="ti ti-bookmarks fs-7 text-danger"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Jenis Pelatihan</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link danger-hover-bg" href="{{ route('pelatihan.index') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-danger rounded-3">
                    <i class="ti ti-books fs-7 text-danger"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Data Pelatihan</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link danger-hover-bg" href="{{ route('pelatihan.report') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-danger rounded-3">
                    <i class="ti ti-file-analytics fs-7 text-danger"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Rekap Pelatihan</span>
            </a>
        </li>

        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Setting</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link warning-hover-bg" href="{{ route('user.show', ['user' => session('user_id')]) }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-warning rounded-3">
                    <i class="ti ti-settings fs-7 text-warning"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Setting Akun</span>
            </a>
        </li>

        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Auth</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link warning-hover-bg" href="{{ route('actionlogout') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-warning rounded-3">
                    <i class="ti ti-login fs-7 text-warning"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Log Out</span>
            </a>
        </li>
    </ul>
</nav>
