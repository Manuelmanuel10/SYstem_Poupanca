<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha o seu Plano — Sistema de Poupança</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f2540 0%, #1a3c5e 50%, #2563a8 100%); min-height: 100vh; }
        .plan-card { border-radius: 16px; border: 2px solid transparent; transition: all 0.3s; cursor: pointer; }
        .plan-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .plan-card.popular { border-color: #2563a8; }
        .popular-badge { background: #2563a8; color: white; font-size: 12px; padding: 4px 12px; border-radius: 20px; }
        .price { font-size: 2.5rem; font-weight: 700; color: #1a3c5e; }
        .feature-item { padding: 6px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        .feature-item:last-child { border: none; }
        .btn-plano { border-radius: 8px; padding: 12px; font-weight: 600; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
<div class="container">
    <div class="text-center mb-5">
        <i class="fas fa-piggy-bank fa-3x text-white mb-3"></i>
        <h2 class="text-white fw-bold">Sistema de Poupança SaaS</h2>
        <p class="text-white opacity-75">Escolha o plano ideal para o seu grupo</p>
        @if(session('aviso'))
            <div class="alert alert-warning d-inline-block">{{ session('aviso') }}</div>
        @endif
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Plano Básico -->
        <div class="col-md-4">
            <div class="card plan-card h-100 p-4">
                <div class="text-center mb-3">
                    <span class="badge bg-secondary mb-2">Básico</span>
                    <div class="price">500 <small style="font-size:1rem;color:#666">MT/mês</small></div>
                    <p class="text-muted">Para grupos pequenos</p>
                </div>
                <div class="mb-4">
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Até 15 membros</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>1 grupo</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Contribuições</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Empréstimos</div>
                    <div class="feature-item"><i class="fas fa-times text-danger me-2"></i>Relatórios PDF</div>
                    <div class="feature-item"><i class="fas fa-times text-danger me-2"></i>Multi-grupos</div>
                </div>
                <form method="POST" action="{{ route('tenant.onboarding.escolher') }}" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plano" value="basico">
                    <button class="btn btn-outline-primary btn-plano w-100">Escolher Básico</button>
                </form>
            </div>
        </div>

        <!-- Plano Standard -->
        <div class="col-md-4">
            <div class="card plan-card popular h-100 p-4" style="transform:scale(1.05)">
                <div class="text-center mb-3">
                    <span class="popular-badge mb-2 d-inline-block">⭐ Mais Popular</span>
                    <div class="price mt-2">1.000 <small style="font-size:1rem;color:#666">MT/mês</small></div>
                    <p class="text-muted">Para grupos médios</p>
                </div>
                <div class="mb-4">
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Até 30 membros</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Até 3 grupos</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Contribuições</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Empréstimos</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Relatórios PDF</div>
                    <div class="feature-item"><i class="fas fa-times text-danger me-2"></i>Multi-grupos ilimitados</div>
                </div>
                <form method="POST" action="{{ route('tenant.onboarding.escolher') }}" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plano" value="standard">
                    <button class="btn btn-primary btn-plano w-100">Escolher Standard</button>
                </form>
            </div>
        </div>

        <!-- Plano Premium -->
        <div class="col-md-4">
            <div class="card plan-card h-100 p-4">
                <div class="text-center mb-3">
                    <span class="badge bg-warning text-dark mb-2">Premium</span>
                    <div class="price">2.000 <small style="font-size:1rem;color:#666">MT/mês</small></div>
                    <p class="text-muted">Para gestores profissionais</p>
                </div>
                <div class="mb-4">
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Membros ilimitados</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Grupos ilimitados</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Contribuições</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Empréstimos</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Relatórios PDF</div>
                    <div class="feature-item"><i class="fas fa-check text-success me-2"></i>Divisão final automática</div>
                </div>
                <form method="POST" action="{{ route('tenant.onboarding.escolher') }}" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plano" value="premium">
                    <button class="btn btn-warning btn-plano w-100 text-dark">Escolher Premium</button>
                </form>
            </div>
        </div>
    </div>

    <p class="text-center text-white opacity-50 mt-4" style="font-size:13px">
        Já tem conta? <a href="{{ route('login') }}" class="text-white">Entrar no sistema</a>
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
