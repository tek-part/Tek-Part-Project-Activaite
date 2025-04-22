
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $setting->name }}-@yield('title')</title>
	<link rel="icon" href="{{ asset('storage/' . $setting->logo) }}" type="image/png">

  <link rel="stylesheet" href="{{ asset('assett/vendors/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/vendors/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/vendors/themify-icons/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/vendors/linericon/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/vendors/owl-carousel/owl.theme.default.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assett/vendors/owl-carousel/owl.carousel.min.css') }}">

  @if (App::getLocale() == 'ar')
  <link href="{{ asset('assett/css/style-rtl.css') }}" rel="stylesheet" type="text/css" />


  @else
  <link href="{{ asset('assett/css/style.css') }}" rel="stylesheet" type="text/css" />

  @endif
