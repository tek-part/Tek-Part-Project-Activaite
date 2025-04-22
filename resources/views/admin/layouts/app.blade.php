<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}" data-startbar="dark" data-bs-theme="light">


<!-- Mirrored from mannatthemes.com/approx/default/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 22 Nov 2024 09:37:00 GMT -->

<head>
    @include('admin.layouts.head')
</head>

<body>

    <!-- Top Bar Start -->
    @include('admin.layouts.header')
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    @include('admin.layouts.sidebar')
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">

        <!-- Page Content-->
        @yield('content')

        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    @include('admin.layouts.scripts')
</body>


</html>
