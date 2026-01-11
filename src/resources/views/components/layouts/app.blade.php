<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'MomCare AI' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CYBERPUNK CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/app.css') }}">

    @livewireStyles
</head>
<body>

    <!-- Background Glow -->
    <div class="bg-glow"></div>

    <main class="page-wrapper">
        {{ $slot }}
    </main>

    <script src="{{ asset('front/js/app.js') }}"></script>
    @livewireScripts
</body>
</html>
