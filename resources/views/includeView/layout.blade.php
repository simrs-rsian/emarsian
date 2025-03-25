<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-SDI RSIA Nganjuk</title>
  <link rel="shortcut icon" href="{{ url('/logo.png') }}" />
  <link rel="stylesheet" href="{{ url('src/assets/css/styles.min.css') }}" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <style>
      /* Mengatur z-index untuk Select2 dropdown agar muncul di depan modal */
      .select2-container {
          z-index: 1055; /* Sesuaikan dengan z-index modal Bootstrap */
      }
      .select2-dropdown {
          z-index: 1056; /* Pastikan lebih tinggi dari modal */
      }
      .collapse {
          display: none;
          transition: all 0.3s ease-in-out;
      }
      .collapse.show {
          display: block;
      }
      .submenu a.active {
          font-weight: bold;
          color: #0d6efd; /* Warna biru Bootstrap */
          background-color: rgba(13, 110, 253, 0.1); /* Latar belakang terang */
          border-radius: 5px;
      }
  </style>

</head>
@yield('css')
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div class="scroll-sidebar" data-simplebar>
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <a class="text-nowrap logo-img ms-0 ms-md-1 w-100" href="{{ route('dashboard') }}">
              <img src="{{ url('/rsia.png') }}" style="width: 150px;" alt="logo"/>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <!-- Sidebar navigation-->
        @if(session()->has('user_id'))
            @include('includeView.navbar')
        @else
            @include('includeView.navbaremployee')
        @endif
        <!-- End Sidebar navigation -->

        <!-- End Sidebar navigation -->

      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
                  <h6 class="date mb-0">Hari Ini : <span id="currentDateTime"></span></h6>
                  <i class="typcn typcn-calendar"></i>
              </a>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="src/assets/images/profile/user1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="{{ 'actionlogout' }}" class="btn btn-outline-primary mx-3 mt-2 d-block shadow-none">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">
          
          @yield('content')
          
          @include('includeView.footer')
        </div>
        
      </div>
    </div>
  </div>
  @yield('js')
  <script src="{{ url('src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ url('src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('src/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ url('src/assets/js/app.min.js') }}"></script>
  <script src="{{ url('src/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ url('src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ url('src/assets/js/dashboard.js') }}"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTables
      $(document).ready(function () {
        let table = $('#employeesTable, #dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "paging": true,
            "searching": true,
            "ordering": true,
        });

        // Cek apakah ada halaman terakhir yang tersimpan di localStorage
        let lastPage = localStorage.getItem("datatable_last_page");
        if (lastPage !== null) {
            table.page(parseInt(lastPage)).draw(false);
        }

        // Simpan halaman terakhir saat user berpindah halaman
        table.on('page', function () {
            localStorage.setItem("datatable_last_page", table.page());
        });
    });

      // Initialize Select2 for multiple elements
      const select2Elements = [
        '.select2-profesi', '.select2-pendidikan', '.select2-jabatan',
        '.select2-keluarga', '.select2-status', '.select2-golongan', '.select2-form'
      ];
      select2Elements.forEach(selector => {
        $(selector).select2({
          theme: 'bootstrap-5',
          width: '100%'
        });
      });

      // Initialize Select2 for modal
      $('#createPesertaPelatihanModal').on('shown.bs.modal', function() {
        $('.pesertaMultiple').select2({
          tags: true,
          width: '100%',
          placeholder: "Pilih Pegawai atau Tambah Baru",
          allowClear: true,
          dropdownParent: $('#createPesertaPelatihanModal')
        });
      });

      // Toggle submenu
      $('.toggle-menu').on('click', function(event) {
        event.preventDefault();
        const targetMenu = $('#' + $(this).data('menu-target'));
        $('.submenu.collapse.show').not(targetMenu).removeClass('show');
        targetMenu.toggleClass('show');
      });

      // Update date and time
      function getCurrentDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return `${now.toLocaleDateString('id-ID', options)}, ${now.toLocaleTimeString('id-ID')}`;
      }
      setInterval(() => {
        $('#currentDateTime').text(getCurrentDateTime());
      }, 1000);

      // Load navmenu on role change
      $('#role-select').on('change', function() {
        const roleId = $(this).val();
        if (roleId) {
          $.get(`/navmenu/get-navmenu/${roleId}`, function(response) {
            const navmenus = response.navmenus;
            $('.menu-checkbox').each(function() {
              const menuId = $(this).data('menu-id');
              $(this).prop('checked', !!navmenus.find(menu => menu.m_id == menuId)?.checked);
            });
          }).fail(function() {
            alert('Gagal mengambil data hak akses.');
          });
        }
      });

      // Update hak akses on checkbox change
      $('.menu-checkbox').on('change', function() {
        const roleId = $('#role-select').val();
        const menuId = $(this).data('menu-id');
        const checked = $(this).is(':checked');
        if (roleId) {
          $.ajax({
            url: `/navmenu/update-hakakses`,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: '{{ csrf_token() }}',
              role_id: roleId,
              menu_id: menuId,
              checked: checked
            }),
            success: function(response) {
              console.log(response.message);
            },
            error: function(xhr) {
              alert('Gagal memperbarui hak akses.');
              console.error(xhr.responseJSON);
            }
          });
        } else {
          alert('Pilih role terlebih dahulu.');
          $(this).prop('checked', !checked);
        }
      });
    });
  </script>
</body>

</html>