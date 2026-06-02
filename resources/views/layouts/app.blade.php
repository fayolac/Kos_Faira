<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rumah Kos Faira')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><text y='14' font-size='14'>🏠</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>

    @include('layouts.navbar')

    <div class="public-content">

        @if(session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                alert("{{ session('success') }}");
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                alert("{{ session('error') }}");
            });
        </script>
        @endif

        @yield('content')
    </div>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer-bottom">
        <h5>
            © 2026 Rumah Kos Faira. All rights reserved
</h5>
    </footer>
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>