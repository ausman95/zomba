<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}; url={{route('login')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>25VICTEMPLEAPP @yield('page')</title>
        <link rel="stylesheet" href="{{asset('vendors/select2/css/select2.css')}}">
    <link rel="icon" href="{{asset('images/logo.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{asset('vendor/bootstrap-5.0.0-beta1-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('vendor/animate.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free-5.13.0-web/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{asset('vendor/sweetalert2-10.15.5/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">

    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script
        src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet"
          href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">


    @yield('meta')
    @yield('stylesheets')
</head>
<body id="body-pd" class="body-pd">
<br>
@include('partials.sidebar')
<!-- Page content -->
<div class="content">
    @yield('content')
</div>

<!--  scripts -->
<script src="{{asset('js/main.js')}}"></script>


<script src="{{asset('vendor/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('vendor/popper.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-5.0.0-beta1-dist/js/bootstrap.bundle.js')}}"></script>

<script src="{{asset('/vendor/sweetalert2-10.15.5/sweetalert2.all.min.js')}}"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="{{asset('vendors/select2/js/select2.full.js')}}"></script>

<script>
    $(document).ready(function () {
        (function () {
            const idleDurationSecs = 9500;
            const warningDurationSecs = 5400;
            let idleTimeout;
            let warningTimeout;

            const resetIdleTimeout = function () {
                clearTimeout(warningTimeout);
                warningTimeout = setTimeout(
                    () =>
                        toastr.warning(
                            "Please refresh the page to remain active",
                            "Your session is about to expire",
                            { timeOut: 1800000 }
                        ),
                    warningDurationSecs * 1000
                );
                clearTimeout(idleTimeout);
                idleTimeout = setTimeout(
                    () => document.getElementById("logout-form").submit(),
                    idleDurationSecs * 1000
                );
            };

            // Key events for reset time
            resetIdleTimeout();
            window.onmousemove = resetIdleTimeout;
            window.onkeypress = resetIdleTimeout;
            window.click = resetIdleTimeout;
            window.onclick = resetIdleTimeout;
            window.touchstart = resetIdleTimeout;
            window.onfocus = resetIdleTimeout;
            window.onchange = resetIdleTimeout;
            window.onmouseover = resetIdleTimeout;
            window.onmouseout = resetIdleTimeout;
            window.onmousemove = resetIdleTimeout;
            window.onmousedown = resetIdleTimeout;
            window.onmouseup = resetIdleTimeout;
            window.onkeypress = resetIdleTimeout;
            window.onkeydown = resetIdleTimeout;
            window.onkeyup = resetIdleTimeout;
            window.onsubmit = resetIdleTimeout;
            window.onreset = resetIdleTimeout;
            window.onselect = resetIdleTimeout;
            window.onscroll = resetIdleTimeout;
        })();
        $("input,textarea").focus(function () {
            $(this).css("background-color", "hsl(0, 100%, 90%)");
        });
        $("input,textarea").blur(function () {
            $(this).css("background-color", "#b4b4b4");
        });
        $('.select-relation').select2({
            placeholder: '== Select Options Below =='
        });

        $('.table').DataTable({
            dom: "Bfrtip",
            //responsive:true,
            buttons: [
                'csv','excel', 'pdf', 'print'
            ],
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.table1').DataTable({
            dom: "Bfrtip",
            //responsive:true,
            buttons: [
                'csv','excel', 'pdf', 'print'
            ],
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.table2').DataTable({
            dom: "Bfrtip",
            //responsive:true,
            buttons: [
                'csv','excel', 'pdf', 'print'
            ],
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.table3').DataTable({
            dom: "Bfrtip",
            //responsive:true,
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
        });
    });
</script>
@if(session()->has('success-notification'))
    <script>
        $(document).ready(function () {
            "use strict";
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{session('success-notification')}}',
            })
        });
    </script>
@elseif(session()->has('error-notification') || count($errors)!=0)
    <script>
        $(document).ready(function () {
            "use strict";
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{session('error-notification')}}',
            })
            $('.table').DataTable({
                dom: "Bfrtip",
                //responsive:true,
                buttons: [
                    'excel', 'pdf', 'print', 'csv', 'copy'
                ],
            });
        });
    </script>
@endif


@yield('scripts')
</body>
</html>
