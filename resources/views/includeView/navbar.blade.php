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

            // Mengambil main menu (tanpa parent)
            $mainmenus = DB::table('navmenus')
                ->whereIn('m_id', $accessables)
                ->where('m_child', 0)
                ->where('m_status', 1)
                ->orderBy('m_order', 'asc')
                ->get();

            // Mengambil submenu dan sub-submenu
            $allmenus = DB::table('navmenus')
                ->whereIn('m_id', $accessables)
                ->where('m_status', 1)
                ->orderBy('m_order', 'asc')
                ->get();
        @endphp

        @foreach ($mainmenus as $main)
            @php
                // Submenu dari main menu
                $currentSubmenus = $allmenus->where('m_child', $main->m_id);
                $isActiveMain = false;

                foreach ($currentSubmenus as $submenu) {
                    // Sub-submenu dari submenu saat ini
                    $currentSubsubmenus = $allmenus->where('m_child', $submenu->m_id);
                    
                    // Cek apakah submenu aktif
                    $isActiveSub = request()->is(trim($submenu->m_link, '/') . '*');

                    // Cek apakah sub-submenu aktif
                    foreach ($currentSubsubmenus as $subsubmenu) {
                        if (request()->is(trim($subsubmenu->m_link, '/') . '*')) {
                            $isActiveSub = true;
                            break;
                        }
                    }

                    // Jika ada submenu aktif, menu utama harus aktif juga
                    if ($isActiveSub) {
                        $isActiveMain = true;
                        break;
                    }
                }
            @endphp

            <li class="sidebar-item has-sub {{ $isActiveMain ? 'active' : '' }}">
                <a href="#" class="sidebar-link toggle-menu" data-menu-target="menu-{{ $main->m_id }}">
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
                                $currentSubsubmenus = $allmenus->where('m_child', $submenu->m_id);
                                $isActiveSub = request()->is(trim($submenu->m_link, '/') . '*');

                                foreach ($currentSubsubmenus as $subsubmenu) {
                                    if (request()->is(trim($subsubmenu->m_link, '/') . '*')) {
                                        $isActiveSub = true;
                                        break;
                                    }
                                }
                            @endphp
                            <li class="nav-item ms-3 {{ $isActiveSub ? 'active' : '' }}">
                                <a href="{{ url($submenu->m_link) }}" class="sidebar-link primary-hover-bg">
                                    <span class="aside-icon p-2 me-2 bg-light-success rounded-3">
                                        <i class="ti ti-menu fs-7 text-success"></i>
                                    </span>
                                    <span class="dropdown-item">{{ $submenu->m_name }}</span>
                                </a>

                                @if ($currentSubsubmenus->isNotEmpty())
                                    <ul class="nav flex-column sub-sub-menu collapse {{ $isActiveSub ? 'show' : '' }}">
                                        @foreach ($currentSubsubmenus as $subsubmenu)
                                            @php
                                                $isActiveSubSub = request()->is(trim($subsubmenu->m_link, '/') . '*');
                                            @endphp
                                            <li class="nav-item ms-4 {{ $isActiveSubSub ? 'active' : '' }}">
                                                <a href="{{ url($subsubmenu->m_link) }}" class="sidebar-link primary-hover-bg">
                                                    <span class="aside-icon p-2 me-2 bg-light-warning rounded-3">
                                                        <i class="ti ti-menu fs-7 text-warning"></i>
                                                    </span>
                                                    <span class="dropdown-item">{{ $subsubmenu->m_name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endforeach
        <ul>
            <li class="sidebar-item">
                <a href="{{ route('actionlogout') }}" class="sidebar-link primary-hover-bg">
                    <span class="aside-icon p-2 me-2 bg-light-danger rounded-3">
                        <i class="ti ti-login fs-7 text-danger"></i>
                    </span>
                    <span class="dropdown-item">Logout</span>
                </a>
            </li>
        </ul>
    </ul>
</nav>
