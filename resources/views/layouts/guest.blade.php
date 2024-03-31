<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('creasi::partials.head')

    <title>{{ \implode(' - ', \array_filter([
        $title,
        config('app.name', 'Laravel')
    ])) }}</title>

    <!-- Scripts -->
    {{-- @vite('resources/client/app.ts') --}}
</head>

<body @class(['font-sans antialiased min-h-screen w-full'])>
    @yield('content')

    <div id="outer-app"></div>
</body>
</html>
