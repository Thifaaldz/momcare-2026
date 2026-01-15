<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <!-- FIX mobile keyboard -->
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>{{ $title ?? 'BidanCare AI - Chat' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="{{ asset('front/css/chat.css') }}">
    @livewireStyles
</head>

<body>

    <!-- LOADING -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo">👶</div>
        <div class="loading-text">Memuat BidanCare AI...</div>
    </div>

    <main id="appRoot">
        {{ $slot }}
    </main>

    @livewireScripts

    <!-- WAJIB defer agar Livewire sudah siap -->
    <script src="{{ asset('front/js/chat.js') }}" defer></script>

    <script>
        document.addEventListener('livewire:init', () => {
            setTimeout(() => {
                document.getElementById('loadingScreen')?.classList.add('hidden');
            }, 300);
        });
    </script>

</body>
</html>
