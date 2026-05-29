<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Poupança — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { width: 250px; min-height: 100vh; background: #1a3c5e; }
        .sidebar a { color: #cdd9e5; text-decoration: none; padding: 12px 20px; display: block; transition: background 0.15s; }
        .sidebar a:hover,
        .sidebar a.active { background: #2563a8; color: white; }
        .sidebar .brand { background: #0f2540; padding: 20px; color: white; font-weight: bold; font-size: 1.1rem; }

        /* Separador visual entre grupos de itens da sidebar */
        .sidebar .nav-section {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #5d7fa0;
            padding: 14px 20px 4px;
            pointer-events: none;
        }

        .main-content { flex: 1; background: #f4f6f9; min-height: 100vh; }
        .top-bar { background: white; padding: 15px 25px; border-bottom: 1px solid #dee2e6; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- ── Sidebar ── --}}
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-piggy-bank me-2"></i>Poupança SaaS
        </div>

        <nav class="mt-2">

            {{-- Visão geral --}}
            <span class="nav-section">Visão Geral</span>
            <a href="{{ route('tenant.dashboard') }}"
               class="{{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('tenant.caixa.index') }}"
               class="{{ request()->routeIs('tenant.caixa*') ? 'active' : '' }}">
                <i class="fas fa-book-open me-2"></i>Livro-Caixa
            </a>

            {{-- Gestão --}}
            <span class="nav-section">Gestão</span>
            <a href="{{ route('tenant.grupos.index') }}"
               class="{{ request()->routeIs('tenant.grupos*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i>Grupos
            </a>
            <a href="{{ route('tenant.membros.index') }}"
               class="{{ request()->routeIs('tenant.membros*') ? 'active' : '' }}">
                <i class="fas fa-user me-2"></i>Membros
            </a>
            <a href="{{ route('tenant.contribuicoes.index') }}"
               class="{{ request()->routeIs('tenant.contribuicoes*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-usd me-2"></i>Contribuições
            </a>
            <a href="{{ route('tenant.emprestimos.index') }}"
               class="{{ request()->routeIs('tenant.emprestimos*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave me-2"></i>Empréstimos
            </a>

            {{-- Relatórios --}}
            <span class="nav-section">Relatórios</span>
            <a href="{{ route('tenant.relatorios.index') }}"
               class="{{ request()->routeIs('tenant.relatorios*') ? 'active' : '' }}">
                <i class="fas fa-file-pdf me-2"></i>Relatórios PDF
            </a>

            {{-- Conta --}}
            <span class="nav-section">Conta</span>
            <a href="{{ route('tenant.onboarding.subscricao') }}"
               class="{{ request()->routeIs('tenant.onboarding.*') ? 'active' : '' }}">
                <i class="fas fa-crown me-2"></i>Minha Subscrição
            </a>

        </nav>
    </div>

    {{-- ── Conteúdo principal ── --}}
    <div class="main-content w-100">

        {{-- Top bar --}}
        <div class="top-bar d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@yield('title')</h5>
            <div class="d-flex align-items-center gap-3">
                {{-- Alerta de subscrição a expirar --}}
                @php
                    $tenant = Auth::user()?->tenant;
                    $diasRestantes = $tenant?->data_expiracao
                        ? now()->diffInDays(\Carbon\Carbon::parse($tenant->data_expiracao), false)
                        : null;
                @endphp
                @if($diasRestantes !== null && $diasRestantes <= 7 && $diasRestantes >= 0)
                    <a href="{{ route('tenant.onboarding.subscricao') }}"
                       class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        Subscrição expira em {{ $diasRestantes }} dia(s)
                    </a>
                @elseif($diasRestantes !== null && $diasRestantes < 0)
                    <a href="{{ route('tenant.onboarding.subscricao') }}"
                       class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                        <i class="fas fa-times-circle"></i>
                        Subscrição expirada
                    </a>
                @endif

                <span class="text-muted">
                    <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </button>
                </form>
            </div>
        </div>

        {{-- Corpo da página --}}
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
