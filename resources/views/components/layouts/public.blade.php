@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Ajude estudantes com a mensalidade escolar. PIX direto para a instituição, nunca pessoal.">
        <meta name="theme-color" content="#0d9488">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

        <title>{{ $title ?? config('app.name') }}</title>

        @if (file_exists(public_path('build/manifest.webmanifest')))
            <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
        @else
            <link rel="manifest" href="{{ asset('manifest.json') }}">
        @endif
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-800 antialiased bg-brand-50 min-h-screen">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white border-b border-brand-100 shadow-sm">
                <div class="max-w-lg mx-auto px-4 py-4 flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-white text-sm font-bold">AE</span>
                        <span class="font-bold text-brand-800 text-lg leading-tight">{{ config('app.name') }}</span>
                    </a>
                </div>
            </header>

            <main class="flex-1">
                {{ $slot }}
            </main>

            <x-layouts.public-footer />
        </div>
        @livewireScripts
    </body>
</html>
