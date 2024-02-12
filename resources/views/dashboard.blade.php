<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Game Collection</title>
<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Favicon & Apple touch  -->
<link rel="icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch/apple-touch-icon-iphone-60x60.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-touch/apple-touch-icon-ipad-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-touch/apple-touch-icon-iphone-retina-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-touch/apple-touch-icon-ipad-retina-152x152.png') }}">

</head>
<body class="font-sans antialiased" id="app">
    <router-view></router-view>
</body>
</html>
