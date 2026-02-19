<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Data Center Manager') }}</title>

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    <body>
        <div style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem; background-color: var(--bg-color);">
            <div style="margin-bottom: 2rem;">
                <a href="/" style="font-size: 2rem; font-weight: bold; color: var(--primary-color); text-decoration: none;">
                    DataCenter Manager
                </a>
            </div>

            <div style="width: 100%; max-width: 500px;">
                {{ $slot }}
            </div>
        </div>
        
        <!-- Custom JS -->
        <script src="{{ asset('js/custom.js') }}"></script>
    </body>
</html>
