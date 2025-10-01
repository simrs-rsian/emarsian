<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-MARSIAN RSIA Nganjuk</title>
  <link rel="stylesheet" href="src/assets/css/styles.min.css" />
  <link rel="shortcut icon" href="logo.png" />

  <style>
    /* Container Utama */
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    /* Styling Carousel */
    .carousel-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .carousel-inner {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    .carousel-image {
      height: 60vh;
      object-fit: cover;
      border-radius: 20px;
    }

    .carousel-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(rgba(0, 0, 0, 0.1), transparent);
      border-radius: 20px;
      pointer-events: none;
    }

    /* Styling Papan Informasi */
    .info-header {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
      color: #ffffff;
      background: rgba(29, 0, 172, 0.7);
      padding: 10px 20px;
      border-radius: 10px;
    }

    body {
      background: url('background.jpg') no-repeat center center fixed;
      background-size: cover;
    }
  </style>

</head>

<body>
  <div class="container-fluid">
    <div class="row login-container">
      <!-- Bagian Carousel -->
      <div class="col-lg-7 order-1 order-lg-0 carousel-container">
        <h2 class="info-header">SELAMAT DATANG DI APLIKASI E-MARSIAN</h2>
        <div id="carouselExample" class="carousel slide" style="width: 80%;" data-bs-ride="carousel">
          <ol class="carousel-indicators">
        <li data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#carouselExample" data-bs-slide-to="1"></li>
        <li data-bs-target="#carouselExample" data-bs-slide-to="2"></li>
          </ol>
          @php
        $settings = DB::table('web_settings')->first();
          @endphp
          <div class="carousel-inner rounded-4 position-relative">
        <div class="carousel-item active">
          @if($settings->coursellink1 != '')
            <img src="{{ $settings->coursellink1 }}" class="d-block w-100 img-fluid carousel-image" alt="">
          @else
            <img src="src/assets/images/products/s1.jpg" class="d-block w-100 img-fluid carousel-image" 
          alt="Pengumuman 1">
          @endif
        </div>
        <div class="carousel-item">
          @if($settings->coursellink2 != '')
            <img src="{{ $settings->coursellink2 }}" class="d-block w-100 img-fluid carousel-image" alt="">
          @else
            <img src="src/assets/images/products/s4.jpg" class="d-block w-100 img-fluid carousel-image" 
          alt="Pengumuman 2">
          @endif
        </div>
        <div class="carousel-item">
          @if($settings->coursellink3 != '')
            <img src="{{ $settings->coursellink3 }}" class="d-block w-100 img-fluid carousel-image" alt="">
          @else
            <img src="src/assets/images/products/s5.jpg" class="d-block w-100 img-fluid carousel-image" 
          alt="Pengumuman 3">
          @endif
        </div>
        <div class="carousel-overlay"></div>
          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
          </button>
        </div>
        <div class="info-footer mt-3 p-3 text-center">
          <h6 class="mb-1"><strong>{{ $settings->name }}</strong></h6>
          <p class="mb-1">Alamat: {{ $settings->address }}, Email: <a href="mailto:{{ $settings->email }}" class="text-primary">{{ $settings->email }}</a>,Telepon: <a href="tel:{{ $settings->phone }}" class="text-primary">{{ $settings->phone }}</a></p>
        </div>
      </div>

      <!-- Bagian Login -->
      <div class="col-lg-4 col-md-8 col-sm-10 mx-auto order-0 order-lg-1">
        <div class="card shadow p-4">
          <div class="text-center mb-3">
          @if (session('status'))
            <div class="alert alert-success mb-3">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
            <img src="rsia.png" style="width: 200px;" alt="logo">
            <h3 class="mt-3" style="font-weight: bold; color:rgb(16, 3, 83);">Login E-MARSIAN</h3>
          </div>
            <ul class="nav nav-tabs mb-3" id="loginTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active bg-primary text-white" id="pegawai-tab" data-bs-toggle="tab" href="#pegawai" role="tab">Pegawai</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab">Admin</a>
            </li>
            </ul>
          <div class="tab-content" id="loginTabContent">
            <div class="tab-pane fade show active" id="pegawai" role="tabpanel">
              <form action="{{ route('actionloginemployee') }}" method="post">
                @csrf
                <div class="mb-3">
                  <label class="form-label">No. Induk Pegawai (NIP)</label>
                  <input type="text" name="username" class="form-control" placeholder="Masukkan NIP">
                </div>
                <div class="mb-4">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
              </form>
            </div>
            <div class="tab-pane fade" id="admin" role="tabpanel">
              <form action="{{ route('actionlogin') }}" method="post">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" placeholder="Username">
                </div>
                <div class="mb-4">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
              </form>
            </div>
             <!-- buat link media sosial -->
            <div class="text-center mt-3">
                <!-- <h5>Follow Us</h5> -->
                @if($settings->instagram != '')
                  <a href="{{ $settings->instagram }}" class="d-inline-block me-3">
                  <i class="ti ti-brand-instagram fs-7 text-primary"></i><span><h6>Instagram</h6></span>
                  </a>
                @else
                  <a href="https://www.instagram.com/" class="d-inline-block me-3">
                  <i class="ti ti-brand-instagram fs-7 text-primary"></i><span><h6>Instagram</h6></span>
                  </a>
                @endif
                @if($settings->facebook != '')
                  <a href="{{ $settings->facebook }}" class="d-inline-block me-3">
                  <i class="ti ti-brand-facebook fs-7 text-primary"></i><span><h6>Facebook</h6></span>
                  </a>
                @else
                <a href="https://www.facebook.com/" class="d-inline-block me-3">
                <i class="ti ti-brand-facebook fs-7 text-primary"></i><span><h6>Facebook</h6></span>
                </a>
                @endif
                @if($settings->twitter != '')
                  <a href="{{ $settings->twitter }}" class="d-inline-block me-3">
                  <i class="ti ti-brand-twitter fs-7 text-primary"></i><span><h6>Twitter</h6></span>
                  </a>
                @else
                  <a href="https://www.twitter.com/" class="d-inline-block me-3">
                  <i class="ti ti-brand-twitter fs-7 text-primary"></i><span><h6>Twitter</h6></span>
                  </a>
                @endif
                @if($settings->youtube != '')
                  <a href="{{ $settings->youtube }}" class="d-inline-block me-3">
                  <i class="ti ti-brand-youtube fs-7 text-primary"></i><span><h6>Youtube</h6></span>
                  </a>
                @else
                  <a href="https://www.youtube.com/" class="d-inline-block me-3">
                  <i class="ti ti-brand-youtube fs-7 text-primary"></i><span><h6>Youtube</h6></span>
                  </a>
                @endif
                @if($settings->website != '')
                  <a href="{{ $settings->website }}" class="d-inline-block ">
                  <i class="ti ti-world fs-7 text-primary"></i><span><h6>Website</h6></span>
                  </a>
                @else
                  <a href="https://www.youtube.com/" class="d-inline-block ">
                  <i class="ti ti-world fs-7 text-primary"></i><span><h6>Website</h6></span>
                  </a>
                @endif
            </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>

  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tabs = document.querySelectorAll('#loginTab .nav-link');
      tabs.forEach(tab => {
      tab.addEventListener('shown.bs.tab', function () {
        tabs.forEach(t => t.classList.remove('bg-primary', 'text-white'));
        this.classList.add('bg-primary', 'text-white');
      });
      });
    });
  </script>
</body>

</html>
