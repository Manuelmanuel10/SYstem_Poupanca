<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f2540 0%, #1a3c5e 50%, #2563a8 100%); min-height: 100vh; }
        .login-card { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-header { background: linear-gradient(135deg, #1a3c5e, #2563a8); border-radius: 16px 16px 0 0; padding: 2rem; text-align: center; }
        .form-control { border-radius: 8px; padding: 12px 16px; border: 1.5px solid #e2e8f0; font-size: 15px; }
        .form-control:focus { border-color: #2563a8; box-shadow: 0 0 0 3px rgba(37,99,168,0.15); }
        .input-group-text { background: #f8fafc; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 8px 0 0 8px; color: #2563a8; }
        .input-group .form-control { border-left: none; border-radius: 0 8px 8px 0; }
        .btn-login { background: linear-gradient(135deg, #1a3c5e, #2563a8); border: none; border-radius: 8px; padding: 12px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px; transition: all 0.3s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(37,99,168,0.4); }
        .form-check-input:checked { background-color: #2563a8; border-color: #2563a8; }
        a { color: #2563a8; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="login-header">
                    <div style="width:70px;height:70px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas fa-piggy-bank fa-2x text-white"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-1">Sistema de Poupança</h4>
                    <p class="text-white opacity-75 mb-0" style="font-size:14px;">Gestão de Grupos Comunitários</p>
                </div>
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4" style="border-radius:0 0 16px 16px;">
                    <small class="text-muted">© 2026 Sistema de Poupança SaaS</small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
