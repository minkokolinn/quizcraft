<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('images/icon.png') }}" rel="icon">

    <!-- Icons CSS -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">



    @vite([
        'resources/css/app.css', 
        'resources/js/app.js', 
        'resources/js/template/main.js', 
        'resources/css/template/style.css',
        ])
    @inertiaHead
</head>

<body id="layout-body">
    @inertia

    <!-- Template JS -->
    {{-- JS files with DOM features won't wont here. must setup in @vite() and vite.config.js --}}
</body>

</html>
