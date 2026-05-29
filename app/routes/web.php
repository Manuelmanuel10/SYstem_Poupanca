<?php

use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\GrupoController;
use App\Http\Controllers\Tenant\MembroController;
use App\Http\Controllers\Tenant\ContribuicaoController;
use App\Http\Controllers\Tenant\EmprestimoController;
use App\Http\Controllers\Tenant\RelatorioController;
use App\Http\Controllers\Tenant\LivroCaixaController;
use App\Http\Controllers\Tenant\OnboardingController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->get('/dashboard', fn() => redirect()->route('tenant.dashboard'));

// ─────────────────────────────────────────────────────────────────────────────
// Todas as rotas do tenant num único grupo (corrige o grupo duplicado anterior)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {

    // ── Módulo 5 — Dashboard ──────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Módulo 5 — Livro-Caixa ───────────────────────────────────────────────
    // NOTA: rota estava em falta — adicionada aqui
    Route::get('/caixa', [LivroCaixaController::class, 'index'])->name('caixa.index');

    // ── Módulo 2 — Grupos + Módulo 7 — Encerramento ──────────────────────────
    // As rotas customizadas devem vir ANTES do resource para não serem
    // interpretadas como parâmetro {grupo}
    Route::get( '/grupos/{grupo}/encerrar',              [GrupoController::class, 'encerrar'])             ->name('grupos.encerrar');
    Route::post('/grupos/{grupo}/confirmar-encerramento',[GrupoController::class, 'confirmarEncerramento'])->name('grupos.confirmar-encerramento');
    Route::resource('grupos', GrupoController::class);

    // ── Módulo 3 — Membros ────────────────────────────────────────────────────
    Route::resource('membros', MembroController::class);

    // ── Módulo 4a — Contribuições ─────────────────────────────────────────────
    Route::resource('contribuicoes', ContribuicaoController::class);

    // ── Módulo 4b — Empréstimos ───────────────────────────────────────────────
    Route::resource('emprestimos', EmprestimoController::class);

    // ── Módulo 6 — Relatórios em PDF ─────────────────────────────────────────
    Route::get('/relatorios',                          [RelatorioController::class, 'index'])       ->name('relatorios.index');
    Route::get('/relatorios/grupo/{grupo}',            [RelatorioController::class, 'extratoGrupo'])->name('relatorios.grupo');
    Route::get('/relatorios/membro/{membro}',          [RelatorioController::class, 'extratoMembro'])->name('relatorios.membro');
    // NOTA: rota do PDF do caixa estava em falta — adicionada aqui
    Route::get('/relatorios/caixa/{grupo}',            [RelatorioController::class, 'extratoCaixa'])->name('relatorios.caixa');

    // ── Módulo 1 — Onboarding / Subscrição ───────────────────────────────────
    Route::get( '/planos',             [OnboardingController::class, 'planos'])      ->name('onboarding.planos');
    Route::post('/planos',             [OnboardingController::class, 'escolherPlano'])->name('onboarding.escolher');
    Route::get( '/subscricao',         [OnboardingController::class, 'subscricao'])  ->name('onboarding.subscricao');
    Route::post('/subscricao/renovar', [OnboardingController::class, 'renovar'])     ->name('onboarding.renovar');


});
use App\Http\Controllers\Admin\TenantController as AdminTenantController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tenants', AdminTenantController::class);
    Route::post('/tenants/{tenant}/suspender', [AdminTenantController::class, 'suspender'])->name('tenants.suspender');
    Route::post('/tenants/{tenant}/reativar',  [AdminTenantController::class, 'reativar']) ->name('tenants.reativar');
});
