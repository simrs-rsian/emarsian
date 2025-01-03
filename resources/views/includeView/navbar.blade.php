<nav class="sidebar-nav">
    <ul id="sidebarnav" class="mb-4 pb-2">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
            <span class="hide-menu"></span>
        </li>
        @php
            use Illuminate\Support\Facades\DB;

            $role_id = session('role');
            
            // Mengambil akses menu berdasarkan role pengguna
            $accessables = DB::table('hak_akses')
                ->where('role_id', $role_id)
                ->pluck('navmenu_id')
                ->toArray();

            // Mengambil main menu (menu tanpa parent) yang diakses pengguna
            $mainmenus = DB::table('navmenus')
                ->whereIn('m_id', $accessables)
                ->where('m_child', 0)
                ->where('m_status', 1)
                ->orderBy('m_order', 'asc')
                ->get();

            // Mengambil submenu (menu dengan parent) yang diakses pengguna
            $submenus = DB::table('navmenus')
                ->whereIn('m_id', $accessables)
                ->where('m_child', '!=', 0)
                ->where('m_status', 1)
                ->orderBy('m_order', 'asc')
                ->get();
        @endphp
        <!-- Menampilkan Main Menu -->
        @foreach ($mainmenus as $main)
            @if ($main->m_id == 1) <!-- Menu Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('dashboard') }}" aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-primary rounded-3">
                            <i class="{{ $main->m_icon }}"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">{{ $main->m_name }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <!-- Menampilkan Menu dengan Submenu -->
        @foreach ($mainmenus as $main)
            @if ($main->m_id > 1 && $main->m_id != 18) <!-- Menu selain Dashboard dan Logout -->
                @php
                    // Submenu untuk menu utama saat ini
                    $currentSubmenus = $submenus->filter(function ($submenu) use ($main) {
                        return $submenu->m_child == $main->m_id;
                    });

                    // Menentukan apakah salah satu submenu aktif
                    $isActiveSubmenu = false;
                    foreach ($currentSubmenus as $submenu) {
                        $baseSubmenuLink = trim($submenu->m_link, '/');
                        if (request()->is($baseSubmenuLink . '*') || request()->is($baseSubmenuLink . '/*')) {
                            $isActiveSubmenu = true;
                            break;
                        }
                    }

                    // Menentukan apakah menu utama aktif berdasarkan URL atau submenu
                    $baseMainLink = trim($main->m_link, '/');
                    $isActiveMain = request()->is($baseMainLink . '*') || $isActiveSubmenu;
                @endphp
                <li class="sidebar-item has-sub {{ $isActiveMain ? 'active' : '' }}">
                    <a href="#" class="sidebar-link primary-hover-bg toggle-menu" data-menu-target="menu-{{ $main->m_id }}">
                        <span class="aside-icon p-2 bg-light-primary rounded-3">
                            <i class="{{ $main->m_icon }}"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">{{ $main->m_name }}</span>
                        <i class="ti ti-chevron-down float-end"></i>
                    </a>
                    <div id="menu-{{ $main->m_id }}" class="submenu collapse {{ $isActiveMain ? 'show' : '' }}">
                        <ul class="nav flex-column sub-menu">
                            @foreach ($currentSubmenus as $submenu)
                                @php
                                    // Membuat URL submenu, mempertimbangkan jika ada parameter dinamis
                                    $submenuLink = $submenu->m_link_child 
                                        ? route($submenu->m_link, [session($submenu->m_link_child)]) 
                                        : ($submenu->m_link ? url($submenu->m_link) : null);

                                    // Menentukan apakah submenu aktif berdasarkan URL dasar
                                    $baseSubmenuLink = trim($submenu->m_link, '/');
                                    $isActiveSub = request()->is($baseSubmenuLink . '*') || request()->is($baseSubmenuLink . '/*');
                                @endphp
                                @if ($submenuLink)
                                    <li class="nav-item ms-3 {{ $isActiveSub ? 'active' : '' }}">
                                        <a href="{{ $submenuLink }}" class="sidebar-link sidebar-link primary-hover-bg">
                                            <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                                                <i class="ti ti-menu fs-7 text-success"></i>
                                            </span>
                                            <span class="dropdown-item">{{ $submenu->m_name }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif
        @endforeach
        
        @foreach ($mainmenus as $main)
            @if ($main->m_id == 18) <!-- Menu Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link primary-hover-bg" href="{{ route('actionlogout') }}" aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-primary rounded-3">
                            <i class="{{ $main->m_icon }}"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">{{ $main->m_name }}</span>
                    </a>
                </li>
            @endif
        @endforeach


    </ul>
</nav>
