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
            <a class="sidebar-link sidebar-link warning-hover-bg" href="{{ route('employee.index') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-warning rounded-3">
                    <i class="ti ti-article fs-7 text-warning"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Data Saya</span>
            </a>
        </li>

        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu">Auth</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link sidebar-link warning-hover-bg" href="{{ route('logoutPegawai') }}" aria-expanded="false">
                <span class="aside-icon p-2 bg-light-warning rounded-3">
                    <i class="ti ti-login fs-7 text-warning"></i>
                </span>
                <span class="hide-menu ms-2 ps-1">Log Out</span>
            </a>
        </li>
    </ul>
</nav>
