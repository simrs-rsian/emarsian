<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-SDI RSIA Nganjuk</title>
  <link rel="stylesheet" href="src/assets/css/styles.min.css" />
  <link rel="shortcut icon" href="logo.png" />
  <style>
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f0f2f5;
    }
    .page-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start; /* Konten mulai dari atas */
      text-align: center;
      height: 100%;
      width: 100%;
    }
    .body-wrapper {
      padding-top: 100px; /* Geser ke bawah dengan padding */
    }
    .card {
      margin: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .header img {
      width: 100px;
      margin-bottom: 10px;
    }
    .header h1 {
      font-size: 24px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper">
    
    <div class="body-wrapper">
      <!-- Header -->
      <div class="header">
        <img src="rsia.png" style="width: 200px;" alt="logo">
        <h1>E-SDI RSIA Nganjuk</h1>
      </div>
      <!-- Content -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="card">
                  <img src="src/assets/images/user.png" style="width: 200px;" class="card-img-top" alt="...">
                  <div class="card-body">
                    <a href="{{ route('employeelogin') }}" class="btn btn-primary">Login Pegawai</a>
                  </div>
                </div>
              </div>
              <!-- Right Column -->
              <div class="col-md-6">
                <div class="card">
                  <img src="src/assets/images/adminlogo.jpg" style="width: 200px;" class="card-img-top" alt="...">
                  <div class="card-body">
                    <a href="{{ route('adminlogin') }}" class="btn btn-primary">Login Admin</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="src/assets/js/sidebarmenu.js"></script>
  <script src="src/assets/js/app.min.js"></script>
  <script src="src/assets/libs/simplebar/dist/simplebar.js"></script>
</body>

</html>
