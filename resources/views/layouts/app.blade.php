<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GEES') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell public-shell bg-slate-950 text-slate-100 antialiased">
        <div class="relative isolate min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[44rem] bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.4),_transparent_32%),radial-gradient(circle_at_15%_12%,_rgba(125,211,252,0.16),_transparent_22%),linear-gradient(180deg,_rgba(15,23,42,1),_rgba(3,7,18,0.98))]"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 -z-10 w-1/2 bg-[radial-gradient(circle_at_center,_rgba(37,99,235,0.18),_transparent_46%)]"></div>
            <div class="pointer-events-none absolute inset-0 -z-10 opacity-30 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:4rem_4rem] [mask-image:radial-gradient(circle_at_center,black,transparent_78%)]"></div>

            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-white/10 bg-slate-950/35">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
