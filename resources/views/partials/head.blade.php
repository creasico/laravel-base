<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" type="image/svg+xml" href="{{ asset('/vendor/creasi/favicon.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('/vendor/creasi/icon-192x192.png') }}">
{{-- <link rel="manifest" href="{{ asset('/build/manifest.webmanifest') }}" /> --}}

<!-- Scripts -->
@vite('resources/client/app.ts')