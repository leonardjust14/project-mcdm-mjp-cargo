<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MJP Cargo') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|fraunces:600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="page">
    <aside class="sidebar">
        <div class="logo">MJP <span>Cargo</span></div>
        <div class="muted">Fleet Selection System</div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('alternatives.index') }}" class="{{ request()->routeIs('alternatives.*') ? 'active' : '' }}">Alternatives</a>
            <a href="{{ route('results.index') }}" class="{{ request()->routeIs('results.*') ? 'active' : '' }}">Results</a>
        </nav>
    </aside>

    <main class="content">
        <div class="header">
            <div>
                <div class="badge">AHP + TOPSIS</div>
                <h1>@yield('title')</h1>
            </div>
            @yield('header-action')
        </div>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash" style="color:#f06867; background: rgba(240, 104, 103, 0.12);">
                Please check the form fields and try again.
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
