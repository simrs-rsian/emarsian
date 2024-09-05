<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>E-SDI RSIA Nganjuk</title>
  <!-- base:css -->
  <link rel="stylesheet" href="/src/assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="/src/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/src/assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="logo.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-start py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <center><img src="rsia.png" style="width: 200px;" alt="logo"></center>
              </div>
              <center>
                <h2 class="fw-light"><b>LOGIN</b></h2>
              </center>
              @if(session('error'))
                <div class="alert alert-danger">
                  <b>Opps!</b> {{ session('error') }}
                </div>
              @endif
              <form action="{{ route('actionlogin') }}" method="post">
                @csrf
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg" id="username" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password">
                </div>
                <div class="mt-3 d-grid gap-2">
                  <button type="submit" class="btn btn-block btn-success btn-lg fw-medium auth-form-btn">Masuk</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="/src/assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="/src/assets/js/off-canvas.js"></script>
  <script src="/src/assets/js/hoverable-collapse.js"></script>
  <script src="/src/assets/js/template.js"></script>
  <script src="/src/assets/js/settings.js"></script>
  <script src="/src/assets/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
