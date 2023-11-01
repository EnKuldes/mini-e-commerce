<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    @yield('title') | {{ config('app.name', 'Laravel') }}
  </title>
  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  {{-- third party css --}}
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  @stack('extra-lib-css')
  @stack('extra-css')
  {{-- third party css end --}}
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  @include('layouts.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('layouts.footer')

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Page specific script -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@stack('extra-lib-js')
@stack('extra-js')
<script>
$(function () {
  bsCustomFileInput.init();

  // Notifikasi
  window['toast'] = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  // Handle Ajax Failed
  function ajaxFailedNotify(response, errorThrown) {
    if (typeof response !== 'object') {
      window['toast'].fire({
        icon: 'error',
        title: errorThrown
      });
    } else {
        // bikin html nya dulu untuk masing masing keterangan error, perlu di percantik
        var textNotif = '';
        if (response['message']) {
            textNotif += response['message'];
        }
        $.each(response['errors'], function(index, value) {
            textNotif += '<div class="alert alert-light bg-light text-dark border-0 m-1" role="alert">' +
                '<i class="dripicons-wrong me-2"></i> ' + value +
                '</div>'
        });
        window['toast'].fire({
          icon: 'error',
          title: errorThrown,
          text: textNotif
        });
    }
  }
});
</script>
</body>
</html>
