<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">

<head>
    @include('website.layouts.head')
</head>

<body>
    <!--================Header Menu Area =================-->
    <header class="header_area">
        @include('website.layouts.header')

    </header>
    <!--================Header Menu Area =================-->


    <main class="side-main">
        @yield('content')
    </main>


    <!-- ================ start footer Area ================= -->
    <footer class="footer-area section-gap">
        @include('website.layouts.footer')
    </footer>
    <!-- ================ End footer Area ================= -->

    @include('website.layouts.scripts')

</body>

</html>
