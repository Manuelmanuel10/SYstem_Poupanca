<?php

use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\GrupoController;
use App\Http\Controllers\Tenant\MembroController;
use App\Http\Controllers\Tenant\ContribuicaoController;
use App\Http\Controllers\Tenant\EmprestimoController;
use App\Http\Controllers\Tenant\RelatorioController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->get('/dashboard', fn() => redirect()->route('tenant.dashboard'));

Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {

    // Dashboard (Módulo 5)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Grupos (Módulo 2) + Encerramento (Módulo 7)
    // IMPORTANTE: as rotas customizadas devem vir ANTES do resource
    Route::get('/grupos/{grupo}/encerrar', [GrupoController::class, 'encerrar'])
        ->name('grupos.encerrar');
    Route::post('/grupos/{grupo}/confirmar-encerramento', [GrupoController::class, 'confirmarEncerramento'])
        ->name('grupos.confirmar-encerramento');
    Route::resource('grupos', GrupoController::class);

    // Membros (Módulo 3)
    Route::resource('membros', MembroController::class);

    // Contribuições (Módulo 4a)
    Route::resource('contribuicoes', ContribuicaoController::class);

    // Empréstimos (Módulo 4b)
    Route::resource('emprestimos', EmprestimoController::class);

    // Relatórios em PDF (Módulo 6)
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/grupo/{grupo}', [RelatorioController::class, 'extratoGrupo'])->name('relatorios.grupo');
    Route::get('/relatorios/membro/{membro}', [RelatorioController::class, 'extratoMembro'])->name('relatorios.membro');
});

use App\Http\Controllers\Tenant\OnboardingController;

Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/planos', [OnboardingController::class, 'planos'])->name('onboarding.planos');
    Route::post('/planos', [OnboardingController::class, 'escolherPlano'])->name('onboarding.escolher');
    Route::get('/subscricao', [OnboardingController::class, 'subscricao'])->name('onboarding.subscricao');
    Route::post('/subscricao/renovar', [OnboardingController::class, 'renovar'])->name('onboarding.renovar');
});
