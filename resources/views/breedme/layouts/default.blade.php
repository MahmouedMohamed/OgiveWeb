<!doctype html>
<html>

<head>
    @include('breedme.includes.head')
</head>

<body>
    <div class="container">

        <header class="row">
            @include('breedme.includes.header')
        </header>

        <div id="main" class="row">

            @yield('content')

        </div>

        <footer class="row">
            @include('breedme.includes.footer')
        </footer>

    </div>
    <!--====== Jquery js ======-->
    <script src="{{asset('landing/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/vendor/modernizr-3.7.1.min.js')}}"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{asset('landing/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/bootstrap.min.js')}}"></script>

    <!--====== Slick js ======-->
    <script src="{{asset('landing/assets/js/slick.min.js')}}"></script>

    <!--====== Magnific Popup js ======-->
    <script src="{{asset('landing/assets/js/jquery.magnific-popup.min.js')}}"></script>

    <!--====== Ajax Contact js ======-->
    <script src="{{asset('landing/assets/js/ajax-contact.js')}}"></script>

    <!--====== Isotope js ======-->
    <script src="{{asset('landing/assets/js/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/isotope.pkgd.min.js')}}"></script>

    <!--====== Scrolling Nav js ======-->
    <script src="{{asset('landing/assets/js/jquery.easing.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/scrolling-nav.js')}}"></script>

    <!--====== Main js ======-->
    <script src="{{asset('landing/assets/js/main.js')}}"></script>

</body>

</html>