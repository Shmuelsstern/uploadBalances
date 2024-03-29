<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel='stylesheet' href= 'css/app.css' >
    </head>
    <body>
        @section('navbar')
            
        @show

        <div class="container">
            @yield('content')
        </div>
        <script src='js/app.js';></script>
        @yield('scripts')
    </body>
</html>
