<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malawi Assemblies of God Church @yield('page')</title>


    <link rel="stylesheet" href="{{asset('vendor/bootstrap-5.0.0-beta1-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/animate.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free-5.13.0-web/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/site.css')}}">

    <link rel="stylesheet" href="{{asset('vendor/sweetalert2-10.15.5/sweetalert2.min.css')}}">



    @yield('meta')
    @yield('stylesheets')
</head>
<body>

@yield('content')
<!--  scripts -->
<script src="{{asset('vendor/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('vendor/popper.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-5.0.0-beta1-dist/js/bootstrap.bundle.js')}}"></script>

@yield('scripts')
<script>
    $(document).ready(function(){
        $("input").focus(function(){
            $(this).css("background-color", "#787878");
        });
        $("input").blur(function(){
            $(this).css("background-color", "#b4b4b4");
        });
    });
</script>
</body>
</html>
