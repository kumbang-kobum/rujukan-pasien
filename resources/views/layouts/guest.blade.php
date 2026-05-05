<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" sizes="1036x32" href="{{ asset('favicon-32x32.png') }}">
        
        <title>{{ config('app.name', 'Rujukan Pasien') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- tambahkan di dalam <head> -->
        <style>
          :root{ --bg-overlay: rgba(255,255,255,.70); } /* atur opacity overlay */
          html,body{height:100%;}
          .site-bg{
            position: fixed; inset: 0;
            background: center / cover no-repeat;
            background-attachment: fixed;
            filter: blur(5px);
            transform: scale(1.06);
            z-index: -1;
            pointer-events: none;
          }
          .site-bg::after{
            content:""; position:absolute; inset:0; background:var(--bg-overlay);
          }
          @media (max-width: 768px){ .site-bg{ filter: blur(8px); } }
        </style>

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- layer background blur -->
        <div class="site-bg" style="background-image:url('{{ asset('images/bg-app.webp') }}')"></div>
    
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>
    
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/90 backdrop-blur-md shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
