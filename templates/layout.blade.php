<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ URL::asset('/vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ URL::asset('/vendor/bootstrap-material-design/dist/css/roboto.min.css') }}" rel="stylesheet" />
        <link href="{{ URL::asset('/vendor/bootstrap-material-design/dist/css/material.min.css') }}" rel="stylesheet" />
        <link href="{{ URL::asset('/vendor/bootstrap-material-design/dist/css/ripples.min.css') }}" rel="stylesheet" />

        <title>Laravel Micro Framework</title>

    </head>

    <body>

        <!-- Your site -->

        @yield('content')

        <!-- Your site ends -->

        <script type="text/javascript" src="{{ URL::asset('/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('/vendor/bootstrap-material-design/dist/js/ripples.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('/vendor/bootstrap-material-design/dist/js/material.min.js') }}"></script>

        <script>
            $(document).ready(function ()
            {
                // This command is used to initialize some elements and make them work properly
                $.material.init();
            });
        </script>

    </body>

</html>
