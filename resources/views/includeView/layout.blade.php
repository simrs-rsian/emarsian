<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-MARSIA Nganjuk</title>
  <link rel="shortcut icon" href="{{ url('/logo.png') }}" />
  <link rel="stylesheet" href="{{ url('src/assets/css/styles.min.css') }}" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <style>
      /* Mengatur z-index untuk Select2 dropdown agar muncul di depan modal */
      .select2-container { z-index: 1055; }
      .select2-dropdown { z-index: 1056; }
      .collapse { display: none; transition: all 0.3s ease-in-out; }
      .collapse.show { display: block; }
      .submenu a.active {
          font-weight: bold;
          color: #0d6efd;
          background-color: rgba(13, 110, 253, 0.1);
          border-radius: 5px;
      }
  </style>
  @yield('css')
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <div class="scroll-sidebar" data-simplebar>
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <a class="text-nowrap logo-img ms-0 ms-md-1 w-100" href="{{ route('dashboard') }}">
              <img src="{{ url('/rsia.png') }}" style="width: 150px;" alt="logo"/>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        @if (session('navmenu') == 'admin')
            @include('includeView.navbar')
        @else
            @include('includeView.navbaremployee')
        @endif

      </div>
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
                  <img src="{{ url('src/assets/images/profile/user1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    @if (session('navmenu') == 'admin')
                      <a href="{{  route('logoutAdmin') }}" class="btn btn-outline-primary mx-3 mt-2 d-block shadow-none">Logout</a>
                    @else
                      <a href="{{ route('logoutPegawai') }}" class="btn btn-outline-primary mx-3 mt-2 d-block shadow-none">Logout</a>
                    @endif
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->

      <div class="container-fluid">
        <div class="row">
          @yield('content')
          @include('includeView.footer')
        </div>
      </div>
    </div>
  </div>

  <!-- ===========================
       SCRIPTS (URUTAN: jQuery -> Bootstrap -> plugins -> init)
       =========================== -->
  <!-- 1) jQuery (HANYA SATU sumber) -->
  <script src="{{ url('src/assets/libs/jquery/dist/jquery.min.js') }}"></script>

  <!-- 2) Bootstrap bundle (HANYA SATU sumber) -->
  <script src="{{ url('src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

  <!-- 3) Plugin / Libs lokal -->
  <script src="{{ url('src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ url('src/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

  <!-- 4) Template scripts -->
  <script src="{{ url('src/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ url('src/assets/js/app.min.js') }}"></script>
  <!-- <script src="{{ url('src/assets/js/dashboard.js') }}"></script> -->

  <!-- 5) DataTables & Buttons (CDN) -->
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

  <!-- 6) Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- ===========================
       Inisialisasi (defensive checks)
       =========================== -->
  <script>
    (function ($) {
      $(function () {
        // Debugging sederhana
        try {
          console.log('jQuery version:', $.fn.jquery);
        } catch (e) {
          console.warn('jQuery tidak terdeteksi');
        }
        console.log('Bootstrap Modal available:', typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined');
        console.log(bootstrap.Modal);
        // DataTables init (hanya jika plugin tersedia dan elemen ada)
        if ($.fn.DataTable) {
          $('#employeesTable, #dataTable').each(function () {
            if (!$.fn.DataTable.isDataTable(this)) {
              let table = $(this).DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                paging: true,
                searching: true,
                ordering: true,
              });

              // restore last page jika ada
              try {
                let lastPage = localStorage.getItem("datatable_last_page");
                if (lastPage !== null) {
                  table.page(parseInt(lastPage)).draw(false);
                }
                table.on('page', function () {
                  localStorage.setItem("datatable_last_page", table.page());
                });
              } catch (e) {
                // ignore localStorage errors (privacy mode, dll)
              }
            }
          });
        }

        // Inisialisasi Select2 (hanya selector yang ada)
        const select2Elements = [
          '.select2-profesi', '.select2-pendidikan', '.select2-jabatan',
          '.select2-keluarga', '.select2-status', '.select2-golongan', '.select2-form',
          '.select2-barang', '.select2-ruang', '.select2-pegawai', '.select2-shift',
        ];
        select2Elements.forEach(selector => {
          if ($(selector).length && $.fn.select2) {
            $(selector).select2({ theme: 'bootstrap-5', width: '100%' });
          }
        });

        // Inisialisasi Select2 di dalam modal saat modal tampil (prevent duplicate init)
        $(document).on('shown.bs.modal', '#createPesertaPelatihanModal', function () {
          if ($.fn.select2) {
            $(this).find('.pesertaMultiple').each(function () {
              // cek apakah sudah di-init
              if (!$(this).data('select2')) {
                $(this).select2({
                  tags: true,
                  width: '100%',
                  placeholder: "Pilih Pegawai atau Tambah Baru",
                  allowClear: true,
                  dropdownParent: $('#createPesertaPelatihanModal')
                });
              }
            });
          }
        });

        
        // Pastikan Select2 di-reinitialize setiap kali modal dibuka
        $('#modalTambahRiwayat').on('shown.bs.modal', function() {
            $('.select2-shifting').select2({
                dropdownParent: $('#modalTambahRiwayat'), // wajib agar muncul di atas modal
                width: '100%',
                theme: 'bootstrap-5' // opsional, sesuaikan dengan tema Anda
            });
        });

        // Toggle submenu
        $(document).on('click', '.toggle-menu', function (event) {
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

        // Load navmenu on role change (guard untuk keberadaan elemen)
        $(document).on('change', '#role-select', function () {
          const roleId = $(this).val();
          if (roleId) {
            $.get(`/navmenu/get-navmenu/${roleId}`, function (response) {
              const navmenus = response.navmenus || [];
              $('.menu-checkbox').each(function () {
                const menuId = $(this).data('menu-id');
                $(this).prop('checked', !!navmenus.find(menu => menu.m_id == menuId)?.checked);
              });
            }).fail(function () {
              alert('Gagal mengambil data hak akses.');
            });
          }
        });

        // Update hak akses on checkbox change (guard)
        $(document).on('change', '.menu-checkbox', function () {
          const roleSelect = $('#role-select');
          if (!roleSelect.length) return;
          const roleId = roleSelect.val();
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
              success: function (response) {
                console.log(response.message);
              },
              error: function (xhr) {
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
    })(jQuery);
  </script>
  <script>
    // Matikan jQuery modal supaya tidak bentrok dengan Bootstrap 5
    if (typeof $.fn.modal !== 'undefined') {
      delete $.fn.modal;
      console.log("jQuery modal plugin dihapus → sekarang pakai Bootstrap 5 native");
    }
  </script>

  <!-- LETAKKAN @yield('js') SETELAH SEMUA LIBRARY DIATAS -->
  @yield('js')

  <!-- Saran debugging: jika error 'modal.js:158' muncul lagi,
       cek di DevTools -> Sources -> lihat path file modal.js (apakah file lokal Anda?)
       jika ada file lokal dengan nama modal.js ganti namanya agar tidak menimpa bootstrap -->
</body>
</html>