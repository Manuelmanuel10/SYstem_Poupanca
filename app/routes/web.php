<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\GrupoController;
use App\Http\Controllers\Tenant\MembroController;
use App\Http\Controllers\Tenant\ContribuicaoController;
use App\Http\Controllers\Tenant\EmprestimoController;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\LivroCaixaController;
use App\Http\Controllers\Tenant\RelatorioController;
use App\Http\Controllers\Tenant\RelatorioEvolucaoController;
use App\Http\Controllers\Tenant\DivisaoFinalController;

Route::get('/', fn() => redirect('/login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('grupos',        GrupoController::class);
    Route::resource('membros',       MembroController::class);
    Route::resource('contribuicoes', ContribuicaoController::class);
    Route::resource('emprestimos',   EmprestimoController::class);

    Route::get('/grupos/{grupo}/encerrar',    [DivisaoFinalController::class, 'encerrar'])->name('grupos.encerrar');
    Route::post('/grupos/{grupo}/confirmar',  [DivisaoFinalController::class, 'confirmar'])->name('grupos.confirmar');

    Route::get('/caixa', [LivroCaixaController::class, 'index'])->name('caixa.index');

    Route::get('/relatorios',                  [RelatorioController::class, 'index'])      ->name('relatorios.index');
    Route::post('/relatorios/grupo',           [RelatorioController::class, 'gerarGrupo']) ->name('relatorios.grupo');
    Route::post('/relatorios/membro',          [RelatorioController::class, 'gerarMembro'])->name('relatorios.membro');
    Route::post('/relatorios/caixa',           [RelatorioController::class, 'gerarCaixa']) ->name('relatorios.caixa');
    Route::post('/relatorios/evolucao/grupo',  [RelatorioEvolucaoController::class, 'grupo']) ->name('relatorios.evolucao.grupo');
    Route::post('/relatorios/evolucao/membro', [RelatorioEvolucaoController::class, 'membro'])->name('relatorios.evolucao.membro');

    Route::get('/planos',              [OnboardingController::class, 'planos'])       ->name('onboarding.planos');
    Route::post('/planos',             [OnboardingController::class, 'escolherPlano'])->name('onboarding.escolher');
    Route::get('/subscricao',          [OnboardingController::class, 'subscricao'])   ->name('onboarding.subscricao');
    Route::post('/subscricao/renovar', [OnboardingController::class, 'renovar'])      ->name('onboarding.renovar');
});
