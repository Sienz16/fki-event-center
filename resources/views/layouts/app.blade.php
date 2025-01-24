<!doctype html>
<html class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- @auth
        @if (Auth::user()->roles->contains('role_name', 'admin') || Auth::user()->roles->contains('role_name', 'event_organizer'))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- admin, organizer -->
        @endif
    @endauth --}}
    @livewireStyles
    <script>
        // Enable back-forward cache
        document.addEventListener('livewire:navigating', () => {
            window.history.pushState({}, '');
        });
    </script>
</head>
<body class="h-full">
    {{ $slot }}
    @livewireScripts
</body>
</html>