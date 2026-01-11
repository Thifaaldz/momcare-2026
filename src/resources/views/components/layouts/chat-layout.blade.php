<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BidanCare AI - Chat' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/css/chat.css') }}">
    @livewireStyles
    <style>
        /* Clean Modern Base Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        
        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-logo {
            font-size: 48px;
            margin-bottom: 16px;
            animation: logoPulse 1.5s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { 
                transform: scale(1);
            }
            50% { 
                transform: scale(1.1);
            }
        }

        .loading-text {
            font-size: 14px;
            color: #667781;
            letter-spacing: 0.5px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Selection color */
        ::selection {
            background: rgba(18, 140, 126, 0.3);
            color: #111;
        }
    </style>
</head>
<body class="bg-[#f0f2f5] text-[#111]">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo">👶</div>
        <div class="loading-text">Memuat BidanCare AI...</div>
    </div>

    <!-- Main Content Slot -->
    {{ $slot }}

    @livewireScripts
    <script src="{{ asset('front/js/chat.js') }}"></script>
    <script>
        // Hide loading screen when Livewire is ready
        document.addEventListener('livewire:init', () => {
            setTimeout(() => {
                const screen = document.getElementById('loadingScreen');
                if (screen) screen.classList.add('hidden');
            }, 300);
        });

        // Fallback hide loading screen
        window.addEventListener('load', () => {
            setTimeout(() => {
                const screen = document.getElementById('loadingScreen');
                if (screen) screen.classList.add('hidden');
            }, 500);
        });
    </script>
</body>
</html>

