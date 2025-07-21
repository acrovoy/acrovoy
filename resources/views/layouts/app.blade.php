<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acrovoy')</title>
    <meta name="description" content="@yield('meta_description', 'Acrovoy — app development, web development, startups, everything for traders')">
    <meta name="keywords" content="@yield('meta_keywords', 'web development, web developers, cryptocurrency, trading')">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:image" content="{{ asset('img/preview.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    <meta name="twitter:image" content="{{ asset('img/preview.png') }}">

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>    
    <title>ACROVOY</title>

    @vite('resources/css/app.css') 
    @vite('resources/css/dashboard.css') 
</head>
<body class="logo-bg" style="background-image: url('/img/bg_logo.jpg');">
    
@livewireScripts
@include('partials.header')
@yield('content')
@include('partials.footer')

</body>
</html>
</body>
</html>