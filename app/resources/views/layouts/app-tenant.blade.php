<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Poupança - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { width: 250px; min-height: 100vh; background: #1a3c5e; }
        .sidebar a { color: #cdd9e5; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #2563a8; color: white; }
        .sidebar .brand { background: #0f2540; padding: 20px; color: white; font-weight: bold; font-size: 1.1rem; }
        .main-content { flex: 1; background: #f4f6f9; min-height: 100vh; }
        .top-bar { background: white; padding: 15px 25px; border-bottom: 1px solid #dee2e6; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <div class="brand"><i class="fas fa-piggy-bank me-2"></i>Poupança SaaS</div>
        <nav class="mt-3">
            <a href="{{ route('tenant.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="{{ route('tenant.grupos.index') }}"><i class="fas fa-users me-2"></i>Grupos</a>
            <a href="{{ route('tenant.membros.index') }}"><i class="fas fa-user me-2"></i>Membros</a>
            <a href="{{ route('tenant.contribuicoes.index') }}"><i class="fas fa-hand-holding-usd me-2"></i>Contribuições</a>
            <a href="{{ route('tenant.emprestimos.index') }}"><i class="fas fa-money-bill-wave me-2"></i>Empréstimos</a>
        </nav>
    </div>
    <div class="main-content w-100">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@yield('title')</h5>
            <div>
                <span class="me-3">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Sair</button>
                </form>
            </div>
        </div>
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>