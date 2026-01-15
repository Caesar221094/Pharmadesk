<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pharmadesk - @yield('title', 'Helpdesk Apotek')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            min-height: 100vh;
            background: #0f172a;
            color: #e5e7eb;
        }
        .sidebar a {
            color: #e5e7eb;
            text-decoration: none;
        }
        .sidebar a.active, .sidebar a:hover {
            background: #1e293b;
        }
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }
        .content-wrapper {
            padding: 1.5rem;
        }
        .badge-status-open { background-color: #0ea5e9; }
        .badge-status-in_review { background-color: #6366f1; }
        .badge-status-for_review { background-color: #6366f1; }
        .badge-status-in_progress { background-color: #f97316; }
        .badge-status-waiting_customer { background-color: #eab308; }
        .badge-status-solved { background-color: #22c55e; }
        .badge-status-closed { background-color: #6b7280; }
    </style>

    @stack('styles')
</head>
<body>
<div class="d-flex">
    <aside class="sidebar p-3">
        <div class="d-flex align-items-center mb-4">
            <i class="fa-solid fa-capsules fa-lg me-2"></i>
            <span class="fw-bold">Pharmadesk</span>
        </div>
        <nav class="nav flex-column gap-1">
            <a href="{{ route('dashboard') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie me-2"></i> Dashboard
            </a>
            <a href="{{ route('tickets.index') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->is('tickets*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket me-2"></i> Tiket Kendala
            </a>
            @php
                $roleSlug = auth()->user()?->role?->slug ?? null;
            @endphp
            @if($roleSlug === 'admin')
                <a href="{{ route('pharmacies.index') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->is('pharmacies*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hospital me-2"></i> Apotek
                </a>
                <a href="{{ route('ticket-categories.index') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->is('ticket-categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group me-2"></i> Kategori
                </a>
                <a href="{{ route('ticket-modules.index') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->is('ticket-modules*') ? 'active' : '' }}">
                    <i class="fa-solid fa-puzzle-piece me-2"></i> Modul Sistem
                </a>
                <a href="{{ route('users.index') }}" class="nav-link rounded-2 px-3 py-2 {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Users
                </a>
            @endif
        </nav>
    </aside>

    <div class="flex-grow-1 d-flex flex-column">
        <header class="topbar px-4 py-2 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
                <small class="text-muted">Helpdesk Kendala Sistem Kasir Apotek</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <span class="text-muted small">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-light text-dark border-secondary" type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login</a>
                @endauth
            </div>
        </header>

        <main class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>
