<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />

    <link rel="stylesheet" href="{{ asset('front-theme/styles/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('front-theme/styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front-theme/fonts/css/fontawesome-all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="manifest" href="{{ asset('/front-theme/_manifest.json') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/front-theme/app/icons/icon-192x192.png') }}">

    @vite(['resources/js/app-front.ts'])
    @inertiaHead
</head>
<body class="theme-light" data-highlight="highlight-red">

@inertia

<script defer src="{{ asset('/front-theme/scripts/bootstrap.min.js') }}"></script>
<script defer src="{{ asset('/front-theme/scripts/custom.js') }}"></script>
</body>
</html>