<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('images/bg-app.webp') }}">
        <link rel="preload" as="image" href="{{ asset('images/logo-rujukan.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">

        <title>{{ config('app.name', 'Rujukan Pasien') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html,
            body {
                min-height: 100%;
            }

            .auth-bg {
                position: fixed;
                inset: 0;
                background: center / cover no-repeat;
                opacity: .14;
                filter: saturate(.8);
                z-index: -2;
                pointer-events: none;
            }

            .auth-bg::after {
                content: "";
                position: absolute;
                inset: 0;
                background: rgba(248, 250, 252, .88);
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased">
        <div class="auth-bg" style="background-image:url('{{ asset('images/bg-app.webp') }}')"></div>

        <main class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6">
            <section class="w-full max-w-md">
                <div class="mb-8 text-center">
                    <a href="{{ url('/') }}" class="inline-flex">
                        <img src="{{ asset('images/logo-rujukan.png') }}" alt="Rujukan Pasien" class="h-14 w-auto">
                    </a>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white px-6 py-8 shadow-xl shadow-slate-900/5 sm:px-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-slate-500">
                    Rujukan Pasien
                </p>
            </section>
        </main>
    </body>
</html>
