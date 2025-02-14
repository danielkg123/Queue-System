<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="icon" type="" href="{{url('/assets/ptmb_favicon.png')}}">
  
  <title>@yield('title', 'Default Title')</title>
</head>
<body>
  <!-- Top Navigation -->
  <div class="top-nav">
    <div class="row align-items-center">
      <div class="col-md-6 col-sm-12 text-md-start text-center">
        <span class="top-nav-menu" style="font-size: 1.3rem; font-weight: bold;">Selamat Datang di Perumda Tirta Manuntung Balikpapan</span>
      </div>
      <div class="col-md-6 col-sm-12 text-md-end text-center">
        <span class="top-nav-menu" style="font-size: 1.2rem; ">
          <a  style="font-size: 1.2rem; color: inherit; text-decoration: none;  font-weight: bold;">
            <i class="fa fa-phone"></i> 0542 - 878991
          </a>
        </span>
        <span class="top-nav-menu" style="font-size: 1.2rem;">
          <a  style="font-size: 1.2rem; color: inherit; text-decoration: none; font-weight: bold;">
            <i class="fa fa-envelope"></i> hello@tirtamanuntung.co.id
          </a>
        </span>
      </div>
    </div>
  </div>
</div>


  <div class="content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
