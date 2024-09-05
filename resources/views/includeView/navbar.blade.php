<nav class="sidebar sidebar-offcanvas position-fixed" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="typcn typcn-device-desktop menu-icon"></i>
                <span class="menu-title">Dashboard</span>
                <!-- <div class="badge badge-danger">new</div> -->
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../../../docs/documentation.html">
                <i class="typcn typcn-mortar-board menu-icon"></i>
                <span class="menu-title">Data Karyawan</span>
            </a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="typcn typcn-document-text menu-icon"></i>
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Unit Kerja</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Data Profesi Pegawai</a></li>              
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Data Pendidikan Pegawai</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html"> Golongan Pegawai</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Status Karyawan</a></li>              
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Status Keluarga</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('actionlogout') }}">
                <i class="typcn typcn-device-desktop menu-icon"></i>
                <span class="menu-title">Logout</span>
                <!-- <div class="badge badge-danger">new</div> -->
            </a>
        </li>
    </ul>
</nav>